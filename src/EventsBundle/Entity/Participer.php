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

