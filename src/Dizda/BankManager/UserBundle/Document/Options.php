<?php
namespace Dizda\BankManager\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;



/** @MongoDB\EmbeddedDocument */
class Options
{
    /** @MongoDB\Boolean */
    private $allow_email;

    /** When this amount is exceded, we send an email to the user
     *  @MongoDB\Float */
    private $alert_amount;

    /** @MongoDB\Date */
    private $last_update;



    public function __construct()
    {

    }

    /**
     * @MongoDB\PreUpdate
     */
    public function preUpdate()
    {
        $this->last_update = new \DateTime();
    }

    /**
     * Set last_update
     *
     * @param date $lastUpdate
     * @return \Options
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->last_update = $lastUpdate;
        return $this;
    }

    /**
     * Get last_update
     *
     * @return date $lastUpdate
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * Set alert_amount
     *
     * @param Float $alertAmount
     * @return \Options
     */
    public function setAlertAmount($alertAmount)
    {
        $this->alert_amount = $alertAmount;
        return $this;
    }

    /**
     * Get alert_amount
     *
     * @return Float $alertAmount
     */
    public function getAlertAmount()
    {
        return $this->alert_amount;
    }

    public function __toString()
    {
        return $this->alert_amount;
    }

    /**
     * Set allow_email
     *
     * @param boolean $allowEmail
     * @return \Options
     */
    public function setAllowEmail($allowEmail)
    {
        $this->allow_email = $allowEmail;
        return $this;
    }

    /**
     * Get allow_email
     *
     * @return boolean $allowEmail
     */
    public function getAllowEmail()
    {
        return $this->allow_email;
    }
}
