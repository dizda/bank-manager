<?php
namespace Dizda\BankManager\CoreBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Dizda\BankManager\UserBundle\Entity\User;


class AccountEvent extends Event
{
    const ACCOUNTS      = 'ACCOUNTS';
    const TRANSACTIONS  = 'TRANSACTIONS';

    protected $accounts;
    protected $transactions;
    private   $user;

    public function __construct($type, $datas, User $user = null)
    {
        if ($type === self::ACCOUNTS) {
            $this->accounts     = $datas;
        } elseif ($type === self::TRANSACTIONS) {
            $this->transactions = $datas;
        }

        $this->user = $user;
    }

    public function getAccounts()
    {
        return $this->accounts;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * @return \Dizda\BankManager\UserBundle\Document\User
     */
    public function getUser()
    {
        return $this->user;
    }

}
