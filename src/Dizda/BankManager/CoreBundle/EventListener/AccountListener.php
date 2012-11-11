<?php
namespace Dizda\BankManager\CoreBundle\EventListener;

use Dizda\BankManager\CoreBundle\Event\AccountEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation as DI;
use Dizda\BankManager\CoreBundle\Document\BalanceHistory;

/**
 * @Service("dizda.bank.listener.account");
 */
class AccountListener
{
    protected $dm;
    protected $statsFetched = [];

    /**
     * @DI\InjectParams({
     *     "dm"         = @DI\Inject("doctrine.odm.mongodb.document_manager")
     * })
     */
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    
    /**
     * Save accounts array into MongoDB
     * @Observe("dizda.bank.account.update")
     */
    public function onAccountEvent(AccountEvent $event)
    {
        foreach($event->getAccounts() as $account)
        {
            $exist = $this->dm->find('DizdaBankManagerCoreBundle:Account', $account->getIban());

            if($exist)
            {
                $balance = $exist->getBalanceHistory()->last();

                if(!$balance)
                {
                    $balance = new BalanceHistory();
                    $balance->setBalance($account->getBalance());

                    $exist->addBalanceHistory($balance);
                    $this->dm->persist($exist);
                }else{

                    if($balance->getBalance() != $account->getBalance())
                    {
                        $balance = new BalanceHistory();
                        $balance->setBalance($account->getBalance());

                        $exist->setBalance($account->getBalance());
                        $exist->addBalanceHistory($balance);
                        $this->dm->persist($exist);
                    }

                }

            }else{
                $this->dm->persist($account);
            }


        }
        $this->dm->flush();
    }
    
    /**
     * @Observe("dizda.bank.transaction.add")
     */
    public function onTransactionAddEvent(AccountEvent $event)
    {
        foreach($event->getTransactions() as $key => $account)
        {
            $accountEntity = $this->dm->find('Dizda\BankManager\CoreBundle\Document\Account', $key);
            $this->statsFetched[$key]['count'] = 0;
            $this->statsFetched[$key]['added'] = 0;
            
            foreach($account as $transaction)
            {
                
                /* If the transaction is already created, we dont overwrite it */
                if( !$this->dm->find('Dizda\BankManager\CoreBundle\Document\Transaction', $transaction->generateId()) )
                {
                    $transaction->setAccount($accountEntity);
                    $this->dm->persist($transaction);
                    $this->statsFetched[$key]['added']++;
                }
                
                $this->statsFetched[$key]['count']++;
                
            }
        }
        $this->dm->flush();
        
        //$event->getDispatcher()->dispatch('log', $event); // http://symfony.com/doc/master/components/event_dispatcher/introduction.html
    }
    
    public function getStatsFetched()
    {
        return $this->statsFetched;
    }
}