<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 06/02/2017
 * Time: 10:54
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Evaluation
 * @package Application\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="mlb_evaluation")
 */
class Evaluation {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\BlogPost", cascade={"persist"})
     * @ORM\Column(name="idPost", type="integer", nullable=false)
     */
    private $idPost;

    /**
     * @ORM\Column(name="score", type="integer", nullable=false)
     */
    private $score;

    /**
     * @return null|integer
     */
    public function getid()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param null|integer $id
     */
    public function setid($id)
    {
        $this->id = $id;
    }

    /**
     * @param integer $idPost
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    }

    /**
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

}
