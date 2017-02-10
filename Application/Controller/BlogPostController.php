<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 05/02/2017
 * Time: 18:31
 */
namespace Application\Controller;

use Application\Entity\BlogPost;
use Application\Manager\FormFactory;
use Application\Manager\GetDoctrine;

class BlogPostController extends \Hoa\Dispatcher\Kit{

    public function indexAdmin(){

        $data = [];

        return array('layout' => 'Back/index.html.twig', 'data' => $data);

    }

    public function index(){

        $data = [];

        $em = GetDoctrine::getEM();

        $data['posts'] = $em->getRepository('Application\Entity\BlogPost')->findAll();

        return array('layout' => 'Back/posts.html.twig', 'data' => $data);

    }

    public function addPost(){

        $data = [];

        if (!empty($_POST)){

            $em = GetDoctrine::getEM();

            $post = new BlogPost();

            foreach ($_POST as $attribute => $value){

                if (strstr($attribute, 'tags-') != false){

                    if ($value == true){

                        $idTag = str_replace('tags-', '', $attribute);
                        $tag = $em->getRepository('Application\Entity\Tag')->findOneById($idTag);
                        $post->addTag($tag);

                    }

                } else {

                    if (!empty($value)){

                        if ($value == "on"){
                            $value = true;
                        }
                        $funcName = "set".ucFirst($attribute);
                        $post->$funcName($value);

                    }

                }

            }


            $em->persist($post);
            $em->flush();

            $_SESSION['messagesSuccess'][] = "Nouveau post enregistré";
            header("Location: http://".$_SERVER['HTTP_HOST']."/admin_posts");
            exit();

        }


        $data['form'] = FormFactory::build('BlogPost');

        return array('layout' => 'Back/addPost.html.twig', 'data' => $data);

    }

    public function mediasManage(){

        $data = [];

        if($folder = opendir('Application/FileBrowser/Files')){

            $autorizedExtentions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
            $files = [];

            while(false !== ($file = readdir($folder))) {

                if($file != '.' && $file != '..'){

                    $exploded = explode(".", $file);
                    $files[$exploded[0]] = $exploded[1];

                }

            }
        }

        $data['pictures'] = $files;



        return array('layout' => 'Back/mediasManage.html.twig', 'data' => $data);

    }

    public function blogMainPage(){

        $data = [];

        return array('layout' => 'Front/blogMainPage.html.twig', 'data' => $data);

    }

    public function deletePost($id){

        $em = GetDoctrine::getEM();
        $em->getRepository('Application\Entity\BlogPost')->delOnePost($id);

        $_SESSION['messagesSuccess'][] = "Post supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_posts");
        exit();

    }

    public function updatePost($id){
        $data = [];

        $em = GetDoctrine::getEM();
        $post = $em->getRepository('Application\Entity\BlogPost')->findOneById($id);

        if (!empty($_POST)){
            $post->resetBool();

            $em->flush();


            foreach ($_POST as $attribute => $value){

                if (strstr($attribute, 'tags-') != false){

                    if ($value == 'on'){
                        $idTag = str_replace('tags-', '', $attribute);
                        $tag = $em->getRepository('Application\Entity\Tag')->findOneById($idTag);
                        $post->addTag($tag);
                    }

                } else {

                    if (!empty($value)){

                        if ($value == "on"){
                            $value = true;
                        }
                        $funcName = "set".ucFirst($attribute);
                        $post->$funcName($value);

                    }

                }

            }


            $lastUpdate = new \DateTime();
            $post->setLastUpdate($lastUpdate);

            $em->flush();

            $_SESSION['messagesSuccess'][] = "Modification du post enregistrée";
            header("Location: http://".$_SERVER['HTTP_HOST']."/admin_posts");
            exit();

        }

        $data['form'] = FormFactory::build("BlogPost", $post);

        return array('layout' => 'Back/updatePost.html.twig', 'data' => $data);
    }

}