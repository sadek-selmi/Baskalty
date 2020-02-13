<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BlogLike
 *
 * @ORM\Table(name="blog_like")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\BlogLikeRepository")
 */
class BlogLike
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
     * @var int
     *
     * @ORM\Column(name="Likes", type="integer")
     */
    private $likes;

    /**
     * @var int
     *
     * @ORM\Column(name="Dislikes", type="integer")
     */
    private $dislikes;


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
     * Set likes
     *
     * @param integer $likes
     *
     * @return BlogLike
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * Get likes
     *
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set dislikes
     *
     * @param integer $dislikes
     *
     * @return BlogLike
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * Get dislikes
     *
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }
}

