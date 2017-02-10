<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 08/02/2017
 * Time: 17:35
 */

namespace Application\Controller;

use Application\Entity\Tag;
use Application\Manager\FormFactory;
use Application\Manager\GetDoctrine;

class TagController extends \Hoa\Dispatcher\Kit{

    public function manageTags(){

        $data = [];

        $em = GetDoctrine::getEM();
        $data['tags'] = $em->getRepository('Application\Entity\Tag')->findAll();

        return array('layout' => 'Back/tags.html.twig', 'data' => $data);

    }

    public function createNewTag(){

        $data = [];

        if (!empty($_POST)){
            $tag = new Tag();
            $tag->setName($_POST['name']);

            $em = GetDoctrine::getEM();
            $em->persist($tag);
            $em->flush();

            $_SESSION['messagesSuccess'][] = "Nouveau Tag enregistré";
            header("Location: http://".$_SERVER['HTTP_HOST']."/admin_tags");
            exit();
        }

        $data['form'] = FormFactory::build('Tag');

        return array('layout' => 'Back/addTag.html.twig', 'data' => $data);

    }

    public function updateTag($id){
        $data = [];

        $em = GetDoctrine::getEM();
        $tag = $em->getRepository('Application\Entity\Tag')->findOneById($id);

        $data['form'] = FormFactory::build("Tag", $tag);

        if (!empty($_POST)){
            $tag->setName($_POST['name']);
            $em->flush();

            $_SESSION['messagesSuccess'][] = "Tag modifié avec succès.";
            header("Location: http://".$_SERVER['HTTP_HOST']."/admin_tags");
            exit();
        }

        return array('layout' => 'Back/updateTag.html.twig', 'data' => $data);
    }

    public function deleteTag($id){

        $em = GetDoctrine::getEM();
        $em->getRepository('Application\Entity\Tag')->delOneTag($id);

        $_SESSION['messagesSuccess'][] = "Tag supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_tags");
        exit();

    }

}