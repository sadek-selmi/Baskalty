<?php

namespace MaintenanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="MaintenanceBundle\Repository\serviceRepository")
 */
class service
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
     * @var string
     *
     * @ORM\Column(name="nom_service", type="string", length=255)
     */
    private $nomService;


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
     * Set nomService
     *
     * @param string $nomService
     *
     * @return service
     */
    public function setNomService($nomService)
    {
        $this->nomService = $nomService;

        return $this;
    }

    /**
     * Get nomService
     *
     * @return string
     */
    public function getNomService()
    {
        return $this->nomService;
    }
}

