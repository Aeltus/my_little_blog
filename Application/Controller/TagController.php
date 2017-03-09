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

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['DelTag_token'] = $token;
        $_SESSION['DelTag_token_time'] = time();

        $data['token'] = $token;

        return array('layout' => 'Back/tags.html.twig', 'data' => $data);

    }

    public function createNewTag(){

        $data = [];
        $tag = new Tag();


        if (!empty($_POST)){

            $tag->setName(trim($_POST['name']));
/*======================================================================================================================
*                                                                                                                      *
*                                  Security verifications before flush                                                 *
*                                                                                                                      *
*=====================================================================================================================*/

            FormFactory::secureCSRF($_POST['token'], 'Tag');

            $security = FormFactory::security('Tag', $tag);

            if (!empty($security)) {

                foreach ($security as $error) {

                    switch ($error){
                        case "name":
                            $_SESSION['messagesWarning'][] = "Entrez un nom valide composé d'au moins une lettre, chiffres et espaces.";
                    }

                }


            // if $security is empty, then any error has been raised, so, we can flush
            } else {

                $em = GetDoctrine::getEM();
                $em->persist($tag);
                $em->flush();

                $_SESSION['messagesSuccess'][] = "Nouveau Tag enregistré";
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/admin_tags");
                exit();
            }
        }

        $data['form'] = FormFactory::build('Tag');

        return array('layout' => 'Back/addTag.html.twig', 'data' => $data);

    }

    public function updateTag($id){
        $data = [];

        $em = GetDoctrine::getEM();
        $tag = $em->getRepository('Application\Entity\Tag')->findOneById($id);

        if (!empty($_POST)){

/*======================================================================================================================
*                                                                                                                      *
*                                  Security verifications before flush                                                 *
*                                                                                                                      *
*=====================================================================================================================*/

            FormFactory::secureCSRF($_POST['token'], 'Tag');

            $security = FormFactory::security('Tag', $tag);

            if (!empty($security)) {

                foreach ($security as $error) {

                    switch ($error){
                        case "name":
                            $_SESSION['messagesWarning'][] = "Entrez un nom valide composé d'au moins une lettre, chiffres et espaces.";
                    }

                }


            // if $security is empty, then any error has been raised, so, we can flush
            } else {

                $tag->setName(trim($_POST['name']));
                $em->flush();

                $_SESSION['messagesSuccess'][] = "Tag modifié avec succès.";
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/admin_tags");
                exit();
            }
        }

        $data['form'] = FormFactory::build("Tag", $tag);

        return array('layout' => 'Back/updateTag.html.twig', 'data' => $data);
    }

    public function deleteTag($id, $token){

        $token = str_replace("_t-", "", $token);
        FormFactory::secureCSRF($token, 'DelTag');

        $em = GetDoctrine::getEM();
        $em->getRepository('Application\Entity\Tag')->delOneTag($id);

        $_SESSION['messagesSuccess'][] = "Tag supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_tags");
        exit();

    }

}
