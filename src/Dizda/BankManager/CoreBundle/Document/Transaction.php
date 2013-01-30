<?php
namespace Dizda\BankManager\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Exclude;

/**
 * @MongoDB\Document(repositoryClass="Dizda\BankManager\CoreBundle\Document\Repository\TransactionRepository")
 * @XmlRoot("ligmvt") */
class Transaction
{
    /**
     * @Exclude
     * @MongoDB\Id(strategy="none")
     */
    protected $id;
    
    /** @Type("DateTime<'Ymd'>")
     *  @SerializedName("dat")
     *  @MongoDB\Date */
    private $date_transaction;
    
    /** @Type("string")
     *  @SerializedName("lib")
     *  @MongoDB\String */
    private $label;
    
    /** @Type("string")
     *  @SerializedName("lib2")
     *  @MongoDB\String */
    private $label2;
    
    /** @Type("double")
     *  @SerializedName("mnt")
     *  @MongoDB\String */
    private $amount;

    /**
     *  @Exclude
     *  @MongoDB\Boolean */
    private $excluded = false;

    /** 
     *  @Exclude
     *  @MongoDB\Date */
    private $date_fetched;

    /**
     *  @Exclude
     *  @MongoDB\Date */
    private $date_pointer;
    
    /** 
     *  @Exclude
     *  @MongoDB\ReferenceOne(targetDocument="Dizda\BankManager\CoreBundle\Document\Account", inversedBy="transactions")
     *  @MongoDB\Index
     */
    private $account;
    
    /** @MongoDB\PrePersist */
    public function prePersist()
    {
        $this->id           = $this->generateId();
        $this->date_fetched = new \DateTime();
    }
    
    public function generateId()
    {
        return sha1($this->date_transaction->format('Ymd') . $this->amount . $this->label . $this->label2);
    }


    
    public function __toString() {
        return $this->date_transaction->format('d/m/Y') . ' - ' . $this->label . ' ' . $this->label2 . ' : ' . $this->amount;
    }

    /**
     * Set id
     *
     * @param custom_id $id
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
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date_transaction
     *
     * @param date $dateTransaction
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
     * @return date $dateTransaction
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
     * @return string $label
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
     * @return string $label2
     */
    public function getLabel2()
    {
        return $this->label2;
    }

    /**
     * Set amount
     *
     * @param string $amount
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
     * @return string $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date_fetched
     *
     * @param date $dateFetched
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
     * @return date $dateFetched
     */
    public function getDateFetched()
    {
        return $this->date_fetched;
    }

    /**
     * Set account
     *
     * @param Dizda\BankManager\CoreBundle\Document\Account $account
     * @return Transaction
     */
    public function setAccount(\Dizda\BankManager\CoreBundle\Document\Account $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account
     *
     * @return Dizda\BankManager\CoreBundle\Document\Account $account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Set date_pointer
     *
     * @param date $datePointer
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
     * @return date $datePointer
     */
    public function getDatePointer()
    {
        return $this->date_pointer;
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
     * @return boolean $excluded
     */
    public function getExcluded()
    {
        return $this->excluded;
    }
}
