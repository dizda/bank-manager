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
     * Bank account login, to access webservices
     *
     * @MongoDB\String
     */
    protected $ws_login;

    /**
     * Bank account password, to access webservices
     *
     * @MongoDB\String
     */
    protected $ws_password;


    /**
     * @MongoDB\Date
     */
    protected $created_at;

    /** @MongoDB\ReferenceMany(targetDocument="Dizda\BankManager\CoreBundle\Document\Account", mappedBy="user") */
    protected $accounts;

    /** @MongoDB\EmbedOne(targetDocument="Dizda\BankManager\UserBundle\Document\Options") */
    private $options;


    public function __construct()
    {
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
        /*$this->options = new \Dizda\BankManager\UserBundle\Document\Options();
        $this->options->setAlertAmount(300);*/

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

    /**
     * Set ws_login
     *
     * @param string $wsLogin
     * @return \User
     */
    public function setWsLogin($wsLogin)
    {
        $this->ws_login = $wsLogin;
        return $this;
    }

    /**
     * Get ws_login
     *
     * @return string $wsLogin
     */
    public function getWsLogin()
    {
        return $this->ws_login;
    }

    /**
     * Set ws_password
     *
     * @param string $wsPassword
     * @return \User
     */
    public function setWsPassword($wsPassword)
    {
        $this->ws_password = $wsPassword;
        return $this;
    }

    /**
     * Get ws_password
     *
     * @return string $wsPassword
     */
    public function getWsPassword()
    {
        return $this->ws_password;
    }

    /**
     * Set options
     *
     * @param Dizda\BankManager\UserBundle\Document\Options $options
     * @return \User
     */
    public function setOptions(\Dizda\BankManager\UserBundle\Document\Options $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get options
     *
     * @return Dizda\BankManager\UserBundle\Document\Options $options
     */
    public function getOptions()
    {
        return $this->options;
    }
}
