<?php

namespace Dizda\BankManager\UserBundle\Document;

use FOS\UserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Date
     */
    protected $created_at;

    /** @MongoDB\ReferenceMany(targetDocument="Dizda\BankManager\CoreBundle\Document\Account", mappedBy="user") */
    protected $accounts;

    public function __construct()
    {
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Add accounts
     *
     * @param Dizda\BankManager\CoreBundle\Document\Account $accounts
     */
    public function addAccounts(\Dizda\BankManager\CoreBundle\Document\Account $accounts)
    {
        $this->accounts[] = $accounts;
    }

    /**
     * Get accounts
     *
     * @return Doctrine\Common\Collections\Collection $accounts
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}
