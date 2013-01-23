<?php
namespace Dizda\BankManager\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 * @ORM\HasLifecycleCallbacks
 * @JMS\XmlRoot("ligmvt")
 */
class Transaction
{

    /**
     * @JMS\Exclude
     * @ORM\Id
     * @ORM\Column(type="string", length=50) */
    protected $id;

    /** @JMS\Type("DateTime<'Ymd'>")
     *  @JMS\SerializedName("dat")
     *  @ORM\Column(type="datetime") */
    private $date_transaction;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("lib")
     *  @ORM\Column(type="string", length=50, nullable=true) */
    private $label;

    /** @JMS\Type("string")
     *  @JMS\SerializedName("lib2")
     *  @ORM\Column(type="string", length=50, nullable=true) */
    private $label2;

    /** @JMS\Type("double")
     *  @JMS\SerializedName("mnt")
     *  @ORM\Column(type="decimal", scale=2) */
    private $amount;

    /**
     *  @JMS\Exclude
     *  @ORM\Column(type="boolean") */
    private $excluded = false;

    /**
     *  @JMS\Exclude
     *  @ORM\Column(type="datetime") */
    private $date_fetched;

    /**
     *  @JMS\Exclude
     *  @ORM\Column(type="datetime", nullable=true) */
    private $date_pointer;

    /** @ORM\ManyToOne(targetEntity="Dizda\BankManager\CoreBundle\Entity\Account")
     *  @ORM\JoinColumn(name="account_iban", referencedColumnName="iban", nullable=false)
     *  @JMS\Exclude */
    protected $account;


    /** @ORM\PrePersist */
    public function prePersist()
    {
        $this->id           = $this->generateId();
        $this->date_fetched = new \DateTime();
    }

    public function generateId()
    {
        return sha1($this->date_transaction->format('Ymd') . $this->amount . $this->label . $this->label2);
    }


    public function __toString()
    {
        return $this->date_transaction->format('d/m/Y') . ' - ' . $this->label . ' ' . $this->label2 . ' : ' . $this->amount;
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Transaction
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date_transaction
     *
     * @param \DateTime $dateTransaction
     * @return Transaction
     */
    public function setDateTransaction($dateTransaction)
    {
        $this->date_transaction = $dateTransaction;
    
        return $this;
    }

    /**
     * Get date_transaction
     *
     * @return \DateTime 
     */
    public function getDateTransaction()
    {
        return $this->date_transaction;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Transaction
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label2
     *
     * @param string $label2
     * @return Transaction
     */
    public function setLabel2($label2)
    {
        $this->label2 = $label2;
    
        return $this;
    }

    /**
     * Get label2
     *
     * @return string 
     */
    public function getLabel2()
    {
        return $this->label2;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set excluded
     *
     * @param boolean $excluded
     * @return Transaction
     */
    public function setExcluded($excluded)
    {
        $this->excluded = $excluded;
    
        return $this;
    }

    /**
     * Get excluded
     *
     * @return boolean 
     */
    public function getExcluded()
    {
        return $this->excluded;
    }

    /**
     * Set date_fetched
     *
     * @param \DateTime $dateFetched
     * @return Transaction
     */
    public function setDateFetched($dateFetched)
    {
        $this->date_fetched = $dateFetched;
    
        return $this;
    }

    /**
     * Get date_fetched
     *
     * @return \DateTime 
     */
    public function getDateFetched()
    {
        return $this->date_fetched;
    }

    /**
     * Set date_pointer
     *
     * @param \DateTime $datePointer
     * @return Transaction
     */
    public function setDatePointer($datePointer)
    {
        $this->date_pointer = $datePointer;
    
        return $this;
    }

    /**
     * Get date_pointer
     *
     * @return \DateTime 
     */
    public function getDatePointer()
    {
        return $this->date_pointer;
    }

    /**
     * Set account
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Account $account
     * @return Transaction
     */
    public function setAccount(\Dizda\BankManager\CoreBundle\Entity\Account $account)
    {
        $this->account = $account;
    
        return $this;
    }

    /**
     * Get account
     *
     * @return \Dizda\BankManager\CoreBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }
}