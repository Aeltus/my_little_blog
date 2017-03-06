<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 05/02/2017
 * Time: 18:31
 */
namespace Application\Controller;

use Application\Entity\BlogPost;
use Application\Entity\Comment;
use Application\Entity\Evaluation;
use Application\Manager\FormFactory;
use Application\Manager\GetDoctrine;

class BlogPostController extends \Hoa\Dispatcher\Kit
{

    public function indexAdmin(){

        $data = [];

        return array('layout' => 'Back/index.html.twig', 'data' => $data);

    }

    public function index(){

        if ( isset($_SERVER['REQUEST_URI']) && strstr($_SERVER['REQUEST_URI'], '/admin_posts') ){
            $layout = 'Back/posts.html.twig';
            $visible = '2';
        } else {
            $layout = 'Front/blogMainPage.html.twig';
            $visible = '1';
        }
        // All variables are setted to the default values
        $data = [];
        $limitStart = 0;
        $number = 10;
        $tag = NULL;
        $orderBy = 'lastUpdate';
        $order = 'DESC';
        $search = NULL;

        $em = GetDoctrine::getEM();


        // if $_GET send variables are setted to the $_GET values
        if (!empty($_GET)){

            if (isset($_GET['visible'])){
                $visible = (int)$_GET['visible'];
            }
            $number = (int)$_GET['number'];
            $orderBy = $_GET['orderBy'];
            $order = $_GET['order'];
            if ($_GET['tag'] != 'all'){
                $tag = (int)$_GET['tag'];
            }
            $limitStart = (int)$_GET['page'];
            if (!empty($_GET['search'])){
                $search = $_GET['search'];
            }

        }

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['DelBlogPost_token'] = $token;
        $_SESSION['DelBlogPost_token_time'] = time();

        $data['token'] = $token;

        // preparing datas to send to twig
        $data['tags'] = $em->getRepository('Application\Entity\Tag')->findAll();
        $data['search'] = ['visible' => $visible, 'limitStart' => $limitStart, 'number' => $number, 'tag' => $tag, 'orderBy' => $orderBy, 'order' => $order, 'search' => $search];
        $data['posts'] = $em->getRepository('Application\Entity\BlogPost')->getPosts($visible, $limitStart, $number, $tag, $orderBy, $order, $search);
        $data['pagination'] = ["start" => $limitStart, "number" => $number, "total" => count($data['posts'])];



        return array('layout' => $layout, 'data' => $data);

    }

    public function viewPostById($id){

        $data = [];
        $em = GetDoctrine::getEM();
        $post = $em->getRepository('Application\Entity\BlogPost')->find($id);
        $nbViews = $post->getNbViews();
        $nbViews++;
        $post->setNbViews($nbViews);
        $em->flush();

        if(!empty($_POST)){


            // if evaluation receved
            if(isset($_POST['note']) && $_POST['note'] < 11){

                FormFactory::secureCSRF($_POST['token'], 'Score');

                $note = (int)$_POST['note'];

                $evaluation = new Evaluation($note, $id);
                $em->persist($evaluation);

                $evaluations = $em->getRepository('Application\Entity\Evaluation')->getEvaluationsForPost($id);
                $nbEval = count($evaluations) + 1;

                $evalSum = 0;
                foreach ($evaluations as $eval){
                    $evalSum = $evalSum + $eval->getScore();
                }

                $evalSum = $evalSum + $note;

                $average = $evalSum / $nbEval;

                $post->setNbEvaluation($nbEval);
                $post->setEvaluation($average);

                $messageSuccess = "Votre note à bien été prise en compte.";


                // set a cookie, an user can rate a post only one time
                if (isset($_COOKIE['evaluations'])){
                    $postsRated = unserialize($_COOKIE['evaluations']);
                } else {
                    $postsRated = [];
                }

                $postsRated[] = $id;

                $cookie = serialize($postsRated);
                setcookie('evaluations', $cookie, time() + 365*24*60*60, null, null, false, true);
                $data['rated'] = true;

            }

            // if comment receved
            if (isset($_POST['comment'])){

                FormFactory::secureCSRF($_POST['token'], 'Comment');

                $comment = new Comment($_POST['author'], $_POST['comment'], $post);
                $em->persist($comment);

                $security = FormFactory::security('Comment', $comment);

                if (strlen($_POST['comment']) > 255){
                    $security[] = "commentLen";
                }

                $messageSuccess = "Votre commentaire à bien été enregistré, il sera en ligne après validation.";

            }

/*======================================================================================================================
*                                                                                                                      *
*                                  Security verifications before flush                                                 *
*                                                                                                                      *
*=====================================================================================================================*/


            if (!empty($security)) {

                foreach ($security as $error) {

                    switch ($error){
                        case "author":
                            $_SESSION['messagesWarning'][] = "Nom invalide, seulement des lettres et des espaces autorisés.";
                        case "commentLen":
                            $_SESSION['messagesWarning'][] = "Commentaire trop long, 255 caractères maximum.";

                    }

                }
            } else {
                $em->flush();
                $_SESSION['messagesSuccess'][] = $messageSuccess;

            }

        }

        $data['post'] = $post;
        $data['comments'] = $em->getRepository('Application\Entity\Comment')->getCommentsForPost($id);


        if (isset($comment) && !empty($security)){
            $data['form'] = FormFactory::build('Comment', $comment);
        } else {
            $data['form'] = FormFactory::build('Comment');
        }

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['Score_token'] = $token;
        $_SESSION['Score_token_time'] = time();
        $data['token'] = $token;

        //get cookie
        if (isset($_COOKIE['evaluations'])) {
            $cookie = unserialize($_COOKIE['evaluations']);

            if (in_array($id, $cookie)) {
                $data['rated'] = true;
            }
        }

        return array('layout' => 'Front/article.html.twig', 'data' => $data);
    }

    public function addPost(){

        $data = [];

        // if $_POST receved
        if (!empty($_POST)){

            $em = GetDoctrine::getEM();

            $post = new BlogPost();

            //$_POST keys are same as BlogPost Attribute names
            foreach ($_POST as $attribute => $value){

                // if attribute contain "tags-" it is a tag, and the number after is the id of the tag
                if (strstr($attribute, 'tags-') != false){

                    if ($value == true){

                        //so, we can get the Tag entity and addIt into the BlogPost entity
                        $idTag = str_replace('tags-', '', $attribute);
                        $tag = $em->getRepository('Application\Entity\Tag')->findOneById($idTag);
                        $post->addTag($tag);

                    }

                } else {

                    if ($attribute != 'token'){

                        // others attributes
                        if (!empty($value)){

                            if ($value == "on"){
                                $value = true;
                            }
                            $funcName = "set".ucFirst($attribute);
                            $post->$funcName($value);

                        }

                    }

                }

            }

/*======================================================================================================================
*                                                                                                                      *
*                                  Security verifications before flush                                                 *
*                                                                                                                      *
*=====================================================================================================================*/

            FormFactory::secureCSRF($_POST['token'], 'BlogPost');

            $security = FormFactory::security('BlogPost', $post);

            if (strlen($post->getAuthor()) > 255){
                $security[] = 'authorLen';
            }
            if (strlen($post->getTitle()) > 255){
            $security[] = 'titleLen';
            }
            if (strlen($post->getHook()) > 255){
            $security[] = 'hookLen';
            }

            if (!empty($security)) {

                foreach ($security as $error) {

                    switch ($error){
                        case "title":
                            $_SESSION['messagesWarning'][] = "Entrez un titre valide composé uniquement de lettres, chiffres et espaces.";
                        case "hook":
                            $_SESSION['messagesWarning'][] = "Entrez un châpo valide composé uniquement de lettres, chiffres, espaces et ponctuation.";
                        case "author":
                            $_SESSION['messagesWarning'][] = "Entrez un nom d'auteur valide composé uniquement de lettres, chiffres et espaces.";
                        case "hookLen":
                        $_SESSION['messagesWarning'][] = "Chapô trop long, 255 caractères maximum.";
                        case "authorLen":
                            $_SESSION['messagesWarning'][] = "Nom d'auteur trop long, 255 caractères maximum.";
                        case "titleLen":
                            $_SESSION['messagesWarning'][] = "Titre trop long, 255 caractères maximum.";
                    }

                }


            // if $security is empty, then any error has been raised, so, we can flush
            } else {

                $em->persist($post);
                $em->flush();

                $_SESSION['messagesSuccess'][] = "Nouveau post enregistré";
                header("Location: http://" . $_SERVER['HTTP_HOST'] . "/admin_posts");
                exit();
            }
        }

        if (isset($post)){
            $data['form'] = FormFactory::build('BlogPost',$post);
        } else {
            $data['form'] = FormFactory::build('BlogPost');
        }

        return array('layout' => 'Back/addPost.html.twig', 'data' => $data);

    }


    /**
     * show all pictures stored on the server
     */
    public function mediasManage(){

        $data = [];

        if($folder = opendir('Application/FileBrowser/Files')){

            $autorizedExtentions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
            $files = [];

            while(false !== ($file = readdir($folder))) {

                if($file != '.' && $file != '..'){

                    $exploded = explode(".", $file);
                    if (in_array($exploded[1], $autorizedExtentions)){
                        $files[$exploded[0]] = $exploded[1];
                    }

                }

            }
        }

        $data['pictures'] = $files;

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['DelMedia_token'] = $token;
        $_SESSION['DelMedia_token_time'] = time();

        $data['token'] = $token;

        return array('layout' => 'Back/mediasManage.html.twig', 'data' => $data);

    }

    /**
     * delete one post
     */
    public function deletePost($id, $token){

        $token = str_replace("_t-", "", $token);
        FormFactory::secureCSRF($token, 'DelBlogPost');

        $em = GetDoctrine::getEM();
        $em->getRepository('Application\Entity\BlogPost')->delOnePost($id);

        $_SESSION['messagesSuccess'][] = "Post supprimé";
        header("Location: http://".$_SERVER['HTTP_HOST']."/admin_posts");
        exit();

    }

    /**
     * @param $id
     *
     * update a post
     */
    public function updatePost($id){
        $data = [];

        // we get the post we are modifying
        $em = GetDoctrine::getEM();
        $post = $em->getRepository('Application\Entity\BlogPost')->findOneById($id);

        // if we get $_POST, we set the $post whit the new values
        if (!empty($_POST)){

            $post->resetBool();

            foreach ($_POST as $attribute => $value){

                // we can't set 'token' as attribute for $post
                if ($attribute != 'token'){

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

            }


            $lastUpdate = new \DateTime();
            $post->setLastUpdate($lastUpdate);

/*======================================================================================================================
*                                                                                                                      *
*                                  Security verifications before flush                                                 *
*                                                                                                                      *
*=====================================================================================================================*/

            FormFactory::secureCSRF($_POST['token'], 'BlogPost');

            $security = FormFactory::security('BlogPost', $post);

            if (!empty($security)) {

                foreach ($security as $error) {

                    switch ($error){
                        case "title":
                            $_SESSION['messagesWarning'][] = "Entrez un titre valide composé uniquement de lettres, chiffres et espaces.";
                        case "hook":
                            $_SESSION['messagesWarning'][] = "Entrez un châpo valide composé uniquement de lettres, chiffres, espaces et ponctuation.";
                        case "author":
                            $_SESSION['messagesWarning'][] = "Entrez un nom d'auteur valide composé uniquement de lettres, chiffres et espaces.";
                    }

                }


            // if $security is empty, then any error has been raised, so, we can flush
            } else {

                $em->flush();

                $_SESSION['messagesSuccess'][] = "Modification du post enregistrée";
                header("Location: http://".$_SERVER['HTTP_HOST']."/admin_posts");
                exit();
            }

/*====================================================================================================================*/

        }

        // if we don't receved $_POST $post serves to hydrate the form
        $data['form'] = FormFactory::build("BlogPost", $post);

        return array('layout' => 'Back/updatePost.html.twig', 'data' => $data);
    }

}
