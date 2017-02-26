<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 08/02/2017
 * Time: 22:56
 */

namespace Application\Controller;

use Application\Manager\FormFactory;
use Application\Manager\GetDoctrine;

class CommentController extends \Hoa\Dispatcher\Kit{

    public function manageComments (){
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $data = [];

        $em = GetDoctrine::getEM();
        $data['comments'] = $em->getRepository('Application\Entity\Comment')->getUnvalidatedComments();

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['DelComment_token'] = $token;
        $_SESSION['DelComment_token_time'] = time();

        $data['token'] = $token;

        return array('layout' => 'Back/comments.html.twig', 'data' => $data);

    }

    public function commentValidate($id){

        $em = GetDoctrine::getEM();

        $comment = $em->getRepository('Application\Entity\Comment')->findOneById($id);
        $comment->setPublished(true);

        $idPost = $comment->getPost()->getId();

        $post = $em->getRepository('Application\Entity\BlogPost')->findOneById($idPost);
        $totComments = $post->getNbComments();
        $totComments++;
        $post->setNbComments($totComments);

        $em->flush();

        $_SESSION['messagesSuccess'][] = "Commentaire validé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_comments");
        exit();

    }

    public function commentRefuse($id, $token){

        $token = str_replace("_t-", "", $token);
        FormFactory::secureCSRF($token, 'DelComment');

        $em = GetDoctrine::getEM();

        $em->getRepository('Application\Entity\Comment')->delOneComment($id);

        $_SESSION['messagesSuccess'][] = "Commentaire supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_comments");
        exit();

    }

}
