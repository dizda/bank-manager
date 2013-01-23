<?php
namespace Dizda\BankManager\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * Every time the balance is gonna change, we'll add the new current balance. (useful to make some stats.. :))

 * @ORM\Entity
 * @ORM\Table(name="account_balance_history")
 */
class AccountBalanceHistory
{

    /**
     * @JMS\Exclude
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO") */
    protected $id;


    /** La balance au moment T
     *  @ORM\Column(type="decimal", scale=2) */
    private $balance;

    /** @ORM\Column(type="datetime") */
    private $date_fetched;

    /** @ORM\ManyToOne(targetEntity="Dizda\BankManager\CoreBundle\Entity\Account")
     *  @ORM\JoinColumn(name="account_iban", referencedColumnName="iban", nullable=false)
     *  @JMS\Exclude */
    protected $account;





    public function __construct()
    {
        $this->date_fetched = new \DateTime();
    }

    public function __toString()
    {
        return $this->date_fetched->format('d/m/Y') . '       ' . $this->balance;
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
     * Set balance
     *
     * @param float $balance
     * @return AccountBalanceHistory
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
     * Set date_fetched
     *
     * @param \DateTime $dateFetched
     * @return AccountBalanceHistory
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
     * Set account
     *
     * @param \Dizda\BankManager\CoreBundle\Entity\Account $account
     * @return AccountBalanceHistory
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