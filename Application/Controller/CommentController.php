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

        $data = [];

        $em = GetDoctrine::getEM();
        $data['comments'] = $em->getRepository('Application\Entity\Comment')->findAll();

        return array('layout' => 'Back/comments.html.twig', 'data' => $data);

    }

    public function commentValidate($id){

        $em = GetDoctrine::getEM();

        $comment = $em->getRepository('Application\Entity\Comment')->findOneById($id);
        $comment->setPublished(true);

        $em->flush();
        $_SESSION['messagesSuccess'][] = "Commentaire validé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_comments");
        exit();

    }

    public function commentRefuse($id){

        $em = GetDoctrine::getEM();

        $comment = $em->getRepository('Application\Entity\Comment')->delOneComment($id);

        $_SESSION['messagesSuccess'][] = "Commentaire supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_comments");
        exit();

    }

}
