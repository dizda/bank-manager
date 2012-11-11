<?php
namespace Dizda\BankManager\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use JMS\SerializerBundle\Annotation\XmlRoot;
use JMS\SerializerBundle\Annotation\Type;
use JMS\SerializerBundle\Annotation\SerializedName;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * @MongoDB\Document
 * @XmlRoot("compte") */
class Account
{
    /** @Type("string")
     *  @MongoDB\Id(strategy="none") */
    private $iban;
    
    /** @Type("string")
     *  @SerializedName("devise")
     *  @MongoDB\String */
    private $currency;
    
    /** @Type("string")
     *  @SerializedName("account_number")
     *  @MongoDB\String */
    private $number;
    
    /** @Type("string")
     *  @SerializedName("intc")
     *  @MongoDB\String */
    private $name;
    
    /** @Type("string")
     *  @SerializedName("int")
     *  @MongoDB\String */
    private $type;
    
    /** @Type("string") 
     *  @SerializedName("tit")
     *  @MongoDB\String */
    private $owner;
    
    /** @Type("double")
     *  @SerializedName("solde")
     *  @MongoDB\Float */
    private $balance;
    
    /** @Type("double")
     *  @MongoDB\Float */
    private $agreed_overdraft;
    
    /** @Type("integer")
     *  @MongoDB\Int */
    private $appcpt;
    
    /** @Type("string")
     *  @MongoDB\String */
    private $webid;
    
    /** @Type("integer")
     *  @MongoDB\Int */
    private $characteristics;
    
    /** @Type("integer")
     *  @MongoDB\Int */
    private $simulation;
    
    /** @Exclude
     *  @MongoDB\ReferenceMany(targetDocument="Dizda\BankManager\CoreBundle\Document\Transaction", mappedBy="account") */
    protected $transactions;
    
    /** @Exclude
     *  @MongoDB\EmbedMany(targetDocument="BalanceHistory") */
    private $balanceHistory = array();
    

    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->balanceHistory = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set iban
     *
     * @param custom_id $iban
     * @return Account
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * Get iban
     *
     * @return custom_id $iban
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Account
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return string $currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Account
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Get number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Account
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Account
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set owner
     *
     * @param string $owner
     * @return Account
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get owner
     *
     * @return string $owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set balance
     *
     * @param float $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Get balance
     *
     * @return float $balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set agreed_overdraft
     *
     * @param float $agreedOverdraft
     * @return Account
     */
    public function setAgreedOverdraft($agreedOverdraft)
    {
        $this->agreed_overdraft = $agreedOverdraft;
        return $this;
    }

    /**
     * Get agreed_overdraft
     *
     * @return float $agreedOverdraft
     */
    public function getAgreedOverdraft()
    {
        return $this->agreed_overdraft;
    }

    /**
     * Set appcpt
     *
     * @param int $appcpt
     * @return Account
     */
    public function setAppcpt($appcpt)
    {
        $this->appcpt = $appcpt;
        return $this;
    }

    /**
     * Get appcpt
     *
     * @return int $appcpt
     */
    public function getAppcpt()
    {
        return $this->appcpt;
    }

    /**
     * Set webid
     *
     * @param string $webid
     * @return Account
     */
    public function setWebid($webid)
    {
        $this->webid = $webid;
        return $this;
    }

    /**
     * Get webid
     *
     * @return string $webid
     */
    public function getWebid()
    {
        return $this->webid;
    }

    /**
     * Set characteristics
     *
     * @param int $characteristics
     * @return Account
     */
    public function setCharacteristics($characteristics)
    {
        $this->characteristics = $characteristics;
        return $this;
    }

    /**
     * Get characteristics
     *
     * @return int $characteristics
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * Set simulation
     *
     * @param int $simulation
     * @return Account
     */
    public function setSimulation($simulation)
    {
        $this->simulation = $simulation;
        return $this;
    }

    /**
     * Get simulation
     *
     * @return int $simulation
     */
    public function getSimulation()
    {
        return $this->simulation;
    }

    /**
     * Add transactions
     *
     * @param Dizda\BankManager\CoreBundle\Document\Transaction $transactions
     */
    public function addTransactions(\Dizda\BankManager\CoreBundle\Document\Transaction $transactions)
    {
        $this->transactions[] = $transactions;
    }

    /**
     * Get transactions
     *
     * @return Doctrine\Common\Collections\Collection $transactions
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Add balanceHistory
     *
     * @param Dizda\BankManager\CoreBundle\Document\BalanceHistory $balanceHistory
     */
    public function addBalanceHistory(\Dizda\BankManager\CoreBundle\Document\BalanceHistory $balanceHistory)
    {
        $this->balanceHistory[] = $balanceHistory;
    }

    /**
     * Get balanceHistory
     *
     * @return Doctrine\Common\Collections\Collection $balanceHistory
     */
    public function getBalanceHistory()
    {
        return $this->balanceHistory;
    }
}
