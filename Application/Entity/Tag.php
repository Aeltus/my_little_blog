<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 06/02/2017
 * Time: 10:50
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag
 * @package Application\Entity
 *
 * @ORM\Table(name="mlb_tag")
 * @ORM\Entity(repositoryClass="Application\Repository\TagRepository")
 */
class Tag {

    /**
     * @ORM\Column(name="idTag", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @form champ=input|type=hidden
     */
    private $idTag = null;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     * @form champ=input|required=true|type=text|class=form-control|placeholder=Tag|label=Nom du tag|security=
     */
    private $name;

    /**
     * @ORM\Column(name="nbPosts", type="integer")
     *
     * @form champ=input|type=hidden
     */
    private $nbPosts = 0;

    /**
     * @return null|integer
     */
    public function getIdTag()
    {
        return $this->idTag;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getNbPosts()
    {
        return $this->nbPosts;
    }

    /**
     * @param null|integer $idTag
     */
    public function setIdTag($idTag)
    {
        $this->idTag = $idTag;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $nbPosts
     */
    public function setNbPosts($nbPosts)
    {
        $this->nbPosts = $nbPosts;
    }

}
