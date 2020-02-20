<?php

namespace MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Map
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="MaintenanceBundle\Repository\MapRepository")
 */
class Map
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
     * @var float
     *
     * @ORM\Column(name="position_user", type="float")
     */
    private $positionUser;
    /**
     * @var float
     *
     * @ORM\Column(name="position_mechanicien", type="float")
     */
private $positionMechanicien;

    /**
     * @return float
     */
    public function getPositionMechanicien()
    {
        return $this->positionMechanicien;
    }

    /**
     * @param float $positionMechanicien
     */
    public function setPositionMechanicien($positionMechanicien)
    {
        $this->positionMechanicien = $positionMechanicien;
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

    /**
     * Set positionUser
     *
     * @param float $positionUser
     *
     * @return Map
     */
    public function setPositionUser($positionUser)
    {
        $this->positionUser = $positionUser;

        return $this;
    }

    /**
     * Get positionUser
     *
     * @return float
     */
    public function getPositionUser()
    {
        return $this->positionUser;
    }
}

