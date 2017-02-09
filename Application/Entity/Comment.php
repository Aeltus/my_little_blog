<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 06/02/2017
 * Time: 10:45
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment
 * @package Application\Entity
 *
 * @ORM\Table(name="mlb_comment")
 * @ORM\Table(indexes={@ORM\Index(name="comment_search", columns={"comment"})})
 * @ORM\Entity(repositoryClass="Application\Repository\CommentRepository")
 */
class Comment {

    /**
     * @ORM\Column(name="idComment", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idComment = null;

    /**
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @ORM\Column(name="author", type="string", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\BlogPost", cascade={"persist"})
     * @ORM\Column(name="idPost", type="integer", nullable=false)
     */
    private $idPost;

    /**
     * @ORM\Column(name="published", type="boolean", nullable=false)
     */
    private $published = null;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->created = new \Datetime();
    }

    /**
     * @return null|integer
     */
    public function getIdComment()
    {
        return $this->idComment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param null|integer $idComment
     */
    public function setIdComment($idComment)
    {
        $this->idComment = $idComment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @param integer $idPost
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @param boolean $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

}
