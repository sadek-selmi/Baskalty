<?php

namespace SellProductsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * panier
 *
 * @ORM\Table(name="panier")
 * @ORM\Entity(repositoryClass="SellProductsBundle\Repository\panierRepository")
 */
class panier
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id"  ,referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="SellProductsBundle\Entity\product", cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id" ,referencedColumnName="id" , nullable=true, onDelete="CASCADE")
     */
    private $produitP;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_p", type="datetime")
     */
    private $dateP;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return mixed
     */
    public function getProduitP()
    {
        return $this->produitP;
    }

    /**
     * @param mixed $produitP
     */
    public function setProduitP($produitP)
    {
        $this->produitP = $produitP;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }



    /**
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return \DateTime
     */
    public function getDateP()
    {
        return $this->dateP;
    }

    /**
     * @param \DateTime $dateP
     */
    public function setDateP($dateP)
    {
        $this->dateP = $dateP;
    }





}
