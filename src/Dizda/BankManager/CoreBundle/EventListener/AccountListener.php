<?php
namespace Dizda\BankManager\CoreBundle\EventListener;

use Dizda\BankManager\CoreBundle\Event\AccountEvent;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation as DI;
use Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory;

use Dizda\BankManager\CoreBundle\Entity\Transaction;
use Dizda\BankManager\UserBundle\Entity\User;

/**
 * @Service("dizda.bank.listener.account");
 */
class AccountListener
{
    protected $em;
    protected $statsFetched = [];

    /**
     * @DI\InjectParams({
     *     "em"           = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    
    /**
     * Save accounts array into MongoDB
     *
     * @Observe("dizda.bank.account.update")
     */
    public function onAccountEvent(AccountEvent $event)
    {
        foreach ($event->getAccounts() as $account) {
            $exist = $this->em->find('DizdaBankManagerCoreBundle:Account', $account->getIban());

            if ($exist) {
                $lastBalance = $exist->getBalanceHistory()->last();


                if (!$lastBalance) {
                    $newBalance = new AccountBalanceHistory();
                    $newBalance->setBalance($account->getBalance());

                    $exist->addBalanceHistory($newBalance);
                    $this->em->persist($exist);
                } else {

                    if ($lastBalance->getBalance() != $account->getBalance()) {

                        $lastBalance = new AccountBalanceHistory();
                        $lastBalance->setBalance($account->getBalance());

                        $exist->setBalance($account->getBalance());
                        $exist->addBalanceHistory($lastBalance);
                        $this->em->persist($exist);
                    }

                }

            } else {
                $account->setUser($event->getUser());
                $this->em->persist($account);
            }


        }
        $this->em->flush();
    }
    
    /**
     * Compare every transactions, and save them if they not exist
     *
     * @Observe("dizda.bank.transaction.add")
     */
    public function onTransactionAddEvent(AccountEvent $event)
    {
        foreach ($event->getTransactions() as $key => $account) {
            $accountEntity = $this->em->find('Dizda\BankManager\CoreBundle\Entity\Account', $key);
            $this->statsFetched[$key]['count'] = 0;
            $this->statsFetched[$key]['added'] = 0;

            foreach ($account as $transaction) {

                /* If the transaction is already created, we dont overwrite it */
                if ( !$this->em->find('Dizda\BankManager\CoreBundle\Entity\Transaction', $transaction->generateId()) ) {
                    $transaction->setAccount($accountEntity);
                    $this->em->persist($transaction); // PLEASE UNCOMMENT, FOR DEV PURPOSE
                    $this->statsFetched[$key]['added']++;
                    $this->sendPush($transaction, $event->getUser());
                }

                $this->statsFetched[$key]['count']++;

            }
        }
        $this->em->flush();

        //$event->getDispatcher()->dispatch('log', $event); // http://symfony.com/doc/master/components/event_dispatcher/introduction.html
    }


    /**
     * If the amount of the transaction is larger than the AlertAmount Option,
     * we send a mail !
     *
     * @param \Dizda\BankManager\CoreBundle\Document\Transaction $transaction
     * @param \Dizda\BankManager\UserBundle\Document\User $user
     */
    public function sendPush(Transaction $transaction, User $user)
    {
        /**
         * We concat user - to AlertAmount (ex. 50, to make -50)
         * Because payments from bank arrive like this float(-51.5),
         * So we have to transform our float(50) to float(-50)
         * And the comparaison symbole ">" is now "<"
         */
        //if (($transaction->getAmount() < (- $user->getOptions()->getAlertAmount())) && $user->getOptions()->getAllowEmail()) {
        if ($transaction->getAmount() && $user->getPushoverToken() && $user->getPushoverUser()) {
            //var_dump($transaction->getAmount()); /* sending mail here ! an eventdispatcher ! */
            curl_setopt_array($ch = curl_init(), array(
                CURLOPT_URL   => "https://api.pushover.net/1/messages.json",
                CURLOPT_POSTFIELDS => array(
                    "token"   =>  $user->getPushoverToken(),
                    "user"    =>  $user->getPushoverUser(),
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