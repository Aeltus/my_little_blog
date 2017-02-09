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
     * @ORM\Column(name="idEvaluation", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEvaluation = null;

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
    public function getIdEvaluation()
    {
        return $this->idEvaluation;
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
     * @param null|integer $idEvaluation
     */
    public function setIdEvaluation($idEvaluation)
    {
        $this->idEvaluation = $idEvaluation;
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
