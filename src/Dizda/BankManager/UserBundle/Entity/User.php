<?php
namespace Dizda\BankManager\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity(repositoryClass="Mv\BlogBundle\Entity\AdminBlog\PostRepository")
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Bank account login, to access webservices
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $ws_login;

    /**
     * Bank account password, to access webservices
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $ws_password;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $created_at;


    /**
     * @ORM\OneToMany(targetEntity="Dizda\BankManager\CoreBundle\Entity\Account", mappedBy="user")
     */
    protected $accounts;


    public function __construct()
    {
        parent::__construct();


        $this->created_at = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ws_login
     *
     * @param string $wsLogin
     * @return User
     */
    public function setWsLogin($wsLogin)
    {
        $this->ws_login = $wsLogin;
    
        return $this;
    }

    /**
     * Get ws_login
     *
     * @return string 
     */
    public function getWsLogin()
    {
        return $this->ws_login;
    }

    /**
     * Set ws_password
     *
     * @param string $wsPassword
     * @return User
     */
    public function setWsPassword($wsPassword)
    {
        $this->ws_password = $wsPassword;
    
        return $this;
    }

    /**
     * Get ws_password
     *
     * @return string 
     */
    public function getWsPassword()
    {
        return $this->ws_password;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Add accounts
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Account $accounts
     * @return User
     */
    public function addAccount(\Dizda\BankManager\CoreBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;
    
        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Account $accounts
     */
    public function removeAccount(\Dizda\BankManager\CoreBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}