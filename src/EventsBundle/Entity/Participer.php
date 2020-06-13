<?php

namespace EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participer
 *
 * @ORM\Table(name="participer")
 * @ORM\Entity(repositoryClass="EventsBundle\Repository\ParticiperRepository")
 */
class Participer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * @ORM\ManyToOne(targetEntity="EventsBundle\Entity\Events", cascade={"persist"})
     * @ORM\JoinColumn(name="events_id" ,referencedColumnName="id" , nullable=true, onDelete="CASCADE")
     */
    private $eventtP;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id"  ,referencedColumnName="id")
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="confirmation", type="boolean")
     */
    private $confirmation;
    /**
     *
     * @ORM\Column(name="champsConfirmation", type="string" ,nullable=true)
     */
    private $champsConfirmation;

    /**
     * @return mixed
     */
    public function getChampsConfirmation()
    {
        return $this->champsConfirmation;
    }

    /**
     * @param mixed $champsConfirmation
     */
    public function setChampsConfirmation($champsConfirmation)
    {
        $this->champsConfirmation = $champsConfirmation;
    }

    /**
     * @return boolean
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @param boolean $confirmation
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }
    /**
     * @return mixed
     */
    public function getEventtP()
    {
        return $this->eventtP;
    }

    /**
     * @param mixed $eventtP
     */
    public function setEventtP($eventtP)
    {
        $this->eventtP = $eventtP;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="Number", type="integer")
     */
    private $Phone=0;

    /**
     * @return int
     */
    public function getPhone()
    {
        return $this->Phone;
    }

    /**
     * @param int $Phone
     */
    public function setPhone($Phone)
    {
        $this->Phone = $Phone;
    }



    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get id
     *
     * @return int
     */

    public function getId()
    {
        return $this->id;
    }

}

