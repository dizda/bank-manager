<?php
namespace Dizda\BankManager\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="account")
 * @JMS\XmlRoot("compte")
 */
class Account
{

    /** @JMS\Type("string")
     *  @ORM\Id
     *  @ORM\Column(type="string", length=50) */
    private $iban;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("devise")
     *  @ORM\Column(type="string", length=10) */
    private $currency;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("account_number")
     *  @ORM\Column(type="string", length=50) */
    private $number;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("intc")
     *  @ORM\Column(type="string", length=70) */
    private $name;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("int")
     *  @ORM\Column(type="string", length=50) */
    private $type;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("tit")
     *  @ORM\Column(type="string", length=50) */
    private $owner;

    /** @JMS\Type("double")
     *  @JMS\SerializedName("solde")
     *  @ORM\Column(type="decimal", scale=2) */
    private $balance;

    /** @JMS\Type("double")
     *  @ORM\Column(type="decimal", scale=2) */
    private $agreed_overdraft;

    /** @JMS\Type("integer")
     *  @ORM\Column(type="smallint", length=2) */
    private $appcpt;

    /** @JMS\Type("string")
     *  @ORM\Column(type="string", length=100) */
    private $webid;

    /** @JMS\Type("integer")
     *  @ORM\Column(type="smallint", length=2) */
    private $characteristics;

    /** @JMS\Type("integer")
     *  @ORM\Column(type="smallint", length=1) */
    private $simulation;

    /**
     * @JMS\Exclude
     * @ORM\OneToMany(targetEntity="Dizda\BankManager\CoreBundle\Entity\Transaction", mappedBy="account")
     */
    protected $transactions;

    /**
     * @JMS\Exclude
     * @ORM\OneToMany(targetEntity="Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory", mappedBy="account", cascade={"persist"})
     */
    private $balanceHistory = array();

    /** @ORM\ManyToOne(targetEntity="Dizda\BankManager\UserBundle\Entity\User")
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *  @JMS\Exclude */
    protected $user;




    /**
     * Set iban
     *
     * @param string $iban
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
     * @return string 
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
     * @return string 
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
     * @return string 
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
     * @return string 
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
     * @return string 
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
     * @return string 
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
     * @return float 
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
     * @return float 
     */
    public function getAgreedOverdraft()
    {
        return $this->agreed_overdraft;
    }

    /**
     * Set appcpt
     *
     * @param integer $appcpt
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
     * @return integer 
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
     * @return string 
     */
    public function getWebid()
    {
        return $this->webid;
    }

    /**
     * Set characteristics
     *
     * @param integer $characteristics
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
     * @return integer 
     */
    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    /**
     * Set simulation
     *
     * @param integer $simulation
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
     * @return integer 
     */
    public function getSimulation()
    {
        return $this->simulation;
    }

    /**
     * Set user
     *
     * @param \Dizda\BankManager\UserBundle\Entity\User $user
     * @return Account
     */
    public function setUser(\Dizda\BankManager\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Dizda\BankManager\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add transactions
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Transaction $transactions
     * @return Account
     */
    public function addTransaction(\Dizda\BankManager\CoreBundle\Entity\Transaction $transactions)
    {
        $this->transactions[] = $transactions;
    
        return $this;
    }

    /**
     * Remove transactions
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Transaction $transactions
     */
    public function removeTransaction(\Dizda\BankManager\CoreBundle\Entity\Transaction $transactions)
    {
        $this->transactions->removeElement($transactions);
    }

    /**
     * Get transactions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    /**
     * Add balanceHistory
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory $balanceHistory
     * @return Account
     */
    public function addBalanceHistory(\Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory $balanceHistory)
    {
        $balanceHistory->setAccount($this); // set balance to the current user
        $this->balanceHistory[] = $balanceHistory;
    
        return $this;
    }

    /**
     * Remove balanceHistory
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory $balanceHistory
     */
    public function removeBalanceHistory(\Dizda\BankManager\CoreBundle\Entity\AccountBalanceHistory $balanceHistory)
    {
        $this->balanceHistory->removeElement($balanceHistory);
    }

    /**
     * Get balanceHistory
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBalanceHistory()
    {
        return $this->balanceHistory;
    }

    public function __toString()
    {
        return $this->iban . ' - ' . $this->name;
    }
}