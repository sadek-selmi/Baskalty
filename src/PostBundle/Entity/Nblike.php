<?php

namespace PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nblike
 *
 * @ORM\Table(name="nblike")
 * @ORM\Entity(repositoryClass="PostBundle\Repository\NblikeRepository")
 */
class Nblike
{
    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")})
     */
    private $user;
    /**
     *
     * @ORM\ManyToOne(targetEntity="PostBundle\Entity\Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="publication", referencedColumnName="id")})
     *
     */
    private $publication;
//    /**
//     *
//     * @ORM\ManyToOne(targetEntity="PostBundle\Entity\Commentaire")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="commentaire", referencedColumnName="id")})
//     *
//     */
//    private $commentaire;

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
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


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

