<?php
namespace Dizda\BankManager\CoreBundle\EventListener;

use Dizda\BankManager\CoreBundle\Event\AccountEvent;
use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation as DI;
use Dizda\BankManager\CoreBundle\Document\BalanceHistory;

use Dizda\BankManager\CoreBundle\Document\Transaction;
use Dizda\BankManager\UserBundle\Document\User;

/**
 * @Service("dizda.bank.listener.account");
 */
class AccountListener
{
    protected $dm;
    protected $statsFetched = [];
    private $push_user;
    private $push_token;

    /**
     * @DI\InjectParams({
     *     "dm"         = @DI\Inject("doctrine.odm.mongodb.document_manager"),
     *     "push_user"    = @DI\Inject("%pushover_user%"),
     *     "push_token"   = @DI\Inject("%pushover_token%")
     * })
     */
    public function __construct(DocumentManager $dm, $push_user, $push_token)
    {
        $this->dm = $dm;
        $this->push_user  = $push_user;
        $this->push_token = $push_token;
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
                    //$this->dm->persist($transaction); // PLEASE UNCOMMENT, FOR DEV PURPOSE
                    $this->statsFetched[$key]['added']++;
                    $this->sendAlertEmail($transaction, $event->getUser());
                }
                
                $this->statsFetched[$key]['count']++;
                
            }
        }
        $this->dm->flush();
        
        //$event->getDispatcher()->dispatch('log', $event); // http://symfony.com/doc/master/components/event_dispatcher/introduction.html
    }


    /**
     * If the amount of the transaction is larger than the AlertAmount Option,
     * we send a mail !
     *
     * @param \Dizda\BankManager\CoreBundle\Document\Transaction $transaction
     * @param \Dizda\BankManager\UserBundle\Document\User $user
     */
    public function sendAlertEmail(Transaction $transaction, User $user)
    {
        /**
         * We concat user - to AlertAmount (ex. 50, to make -50)
         * Because payments from bank arrive like this float(-51.5),
         * So we have to transform our float(50) to float(-50)
         * And the comparaison symbole ">" is now "<"
         */
        if(($transaction->getAmount() < (- $user->getOptions()->getAlertAmount())) && $user->getOptions()->getAllowEmail())
        {
            //var_dump($transaction->getAmount()); /* sending mail here ! an eventdispatcher ! */
            curl_setopt_array($ch = curl_init(), array(
                CURLOPT_URL   => "https://api.pushover.net/1/messages.json",
                CURLOPT_POSTFIELDS => array(
                    "token"   =>  $this->push_token,
                    "user"    =>  $this->push_user,
                    "message" => $transaction->getAmount() . "€ from account " . $transaction->getAccount()->getName() . "\n" .
                                 "New solde " . $transaction->getAccount()->getBalance() . " (before " . $transaction->getAccount()->getBalanceHistory()->offsetGet($transaction->getAccount()->getBalanceHistory()->count() - 2)->getBalance() . ")\n" .
                                 $transaction->getLabel() . "\n" .
                                 $transaction->getLabel2() . "\n",
                ))); /* offsetGet, obtain old balance to add in notification. count all balancehistory and get -2 */
            curl_exec($ch);
            curl_close($ch);
        }
    }



    public function getStatsFetched()
    {
        return $this->statsFetched;
    }
}