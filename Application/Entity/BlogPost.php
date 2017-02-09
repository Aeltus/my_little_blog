<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 06/02/2017
 * Time: 10:28
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @package Application\Entity
 *
 * @ORM\Table(name="mlb_blogPost")
 * @ORM\Table(indexes={@ORM\Index(name="blogPost_search", columns={"content"})})
 * @ORM\Entity(repositoryClass="Application\Repository\BlogPostRepository")
 */
class BlogPost {

    /**
     * @ORM\Column(name="idBlogPost", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @form champ=input|type=hidden
     */
    private $idPost = NULL;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @form champ=input|required=true|type=text|class=form-control|placeholder=Titre|label=Titre du post|security=
     */
    private $title;

    /**
     * @ORM\Column(name="hook", type="string", nullable=false)
     *
     * @form champ=textarea|required=true|rows=5|class=form-control|placeholder=Entête du post|label=Entête du post|security=
     */
    private $hook;

    /**
     * @ORM\Column(name="headerPicture", type="string")
     *
     * @form champ=input|type=hidden|class=form-control|placeholder=Image d'entête|label=Image d'entête|button=imageFinder|remplacedBy=pictureFinder|security=
     */
    private $headerPicture;

    /**
     * @ORM\Column(name="content", type="text", nullable=false)
     *
     * @form champ=textarea|required=true|rows=10|class=form-control|placeholder=Contenu du post|value=|security=
     */
    private $content;

    /**
     * @ORM\Column(name="author", type="string", nullable=false)
     *
     * @form champ=input|required=true|type=text|class=form-control|placeholder=Nom de l'auteur de l'article|label=Auteur de l'article|security=
     */
    private $author;

    /**
     * @ORM\Column(name="lastUpdate", type="datetime")
     *
     * @form champ=input|type=hidden
     */
    private $lastUpdate;

    /**
     * @ORM\Column(name="visible", type="boolean")
     *
     * @form champ=input|type=checkbox|class=form-control|label=Visible sur le site
     */
    private $visible = false;

    /**
     * @ORM\Column(name="commentsActivated", type="boolean")
     *
     * @form champ=input|type=checkbox|class=form-control|label=Activer les commentaires
     */
    private $commentsActivated = false;

    /**
     * @ORM\Column(name="nbViews", type="integer")
     *
     * @form champ=input|type=hidden
     */
    private $nbViews = 0;

    /**
     * @ORM\Column(name="nbComments", type="integer")
     *
     * @form champ=input|type=hidden
     */
    private $nbComments = 0;

    /**
     * @ORM\Column(name="evaluation", type="decimal")
     *
     * @form champ=input|type=hidden
     */
    private $evaluation;

    /**
     * @ORM\Column(name="nbEvaluation", type="integer")
     *
     * @form champ=input|type=hidden
     */
    private $nbEvaluation = 0;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="mlb_tag")
     *
     * @form champ=input|type=checkbox|class=form-control|required=true|externalAttribute=Tag|label=Tag
     */
    private $tags;

    public function __construct()
    {
        $this->lastUpdate = new \Datetime();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return null|integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @return string
     */
    public function getHeaderPicture()
    {
        return $this->headerPicture;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return \Datetime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @return bool
     */
    public function getCommentsActivated()
    {
        return $this->commentsActivated;
    }

    /**
     * @return integer
     */
    public function getNbViews()
    {
        return $this->nbViews;
    }

    /**
     * @return integer
     */
    public function getNbComments()
    {
        return $this->nbComments;
    }

    /**
     * @return float
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * @return integer
     */
    public function getNbEvaluation()
    {
        return $this->nbEvaluation;
    }

    /**
     * @return arrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param null $idPost
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $hook
     */
    public function setHook($hook)
    {
        $this->hook = $hook;
    }

    /**
     * @param string $headerPicture
     */
    public function setHeaderPicture($headerPicture)
    {
        $this->headerPicture = $headerPicture;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @param \Datetime $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @param bool $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @param bool $commentsActivated
     */
    public function setCommentsActivated($commentsActivated)
    {
        $this->commentsActivated = $commentsActivated;
    }

    /**
     * @param integer $nbViews
     */
    public function setNbViews($nbViews)
    {
        $this->nbViews = $nbViews;
    }

    /**
     * @param integer $nbComments
     */
    public function setNbComments($nbComments)
    {
        $this->nbComments = $nbComments;
    }

    /**
     * @param float $evaluation
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * @param integer $nbEvaluation
     */
    public function setNbEvaluation($nbEvaluation)
    {
        $this->nbEvaluation = $nbEvaluation;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * @param Tag $tag
     */
    public function removeCategory(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

}
