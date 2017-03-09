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
 * @ORM\Table(name="mlb_comment", indexes={@ORM\Index(name="comment_search", columns={"comment"})})
 * @ORM\Entity(repositoryClass="Application\Repository\CommentRepository")
 */
class Comment {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * #form champ=input|type=hidden
     */
    private $id = null;

    /**
     * @ORM\Column(name="author", type="string", nullable=false)
     *
     * #form champ=input|required=true|type=text|class=search inputSearch|placeholder=Votre nom|label=Nom|security=#^[a-zA-Z0-9 ]+$#
     */
    private $author;

    /**
     * @ORM\Column(name="comment", type="string")
     *
     * #form champ=textarea|required=true|rows=5|class=search inputSearch|placeholder=Votre commentaire|label=Votre commentaire|security=
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\BlogPost")
     * @ORM\JoinColumn(nullable=false)
     *
     * #form champ=input|type=hidden
     */
    private $post;

    /**
     * @ORM\Column(name="published", type="boolean", nullable=false)
     *
     * #form champ=input|type=hidden
     */
    private $published = false;

    /**
     * @ORM\Column(name="created", type="datetime")
     *
     * #form champ=input|type=hidden
     */
    private $created;

    public function __construct($author, $comment,BlogPost $post)
    {
        $this->created = new \Datetime();
        $this->author = $author;
        $this->comment = $comment;
        $this->post = $post;
    }

    /**
     * @return null|integer
     */
    public function getid()
    {
        return $this->id;
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
    public function getPost()
    {
        return $this->post;
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
     * @param null|integer $id
     */
    public function setid($id)
    {
        $this->id = $id;
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
    public function setPost(BlogPost $post)
    {
        $this->post = $post;
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
