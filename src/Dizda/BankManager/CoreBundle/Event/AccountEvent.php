<?php
namespace Dizda\BankManager\CoreBundle\Event;


use Symfony\Component\EventDispatcher\Event;


class AccountEvent extends Event
{
    const ACCOUNTS      = 'ACCOUNTS';
    const TRANSACTIONS  = 'TRANSACTIONS';
    
    protected $accounts;
    protected $transactions;

    public function __construct($type, $datas)
    {
        if($type === self::ACCOUNTS)
            $this->accounts     = $datas;
        elseif($type === self::TRANSACTIONS)
            $this->transactions = $datas;
    }
    
    public function getAccounts() {
        return $this->accounts;
    }
    
    public function getTransactions() {
        return $this->transactions;
    }

}
