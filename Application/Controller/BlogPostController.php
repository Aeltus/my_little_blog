<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 05/02/2017
 * Time: 18:31
 */
namespace Application\Controller;

use Application\Manager\FormFactory;

class BlogPostController extends \Hoa\Dispatcher\Kit{

    public function indexAdmin(){

        $data = [];

        return array('layout' => 'Back/index.html.twig', 'data' => $data);

    }

    public function index(){

        $data = [];

        return array('layout' => 'Back/posts.html.twig', 'data' => $data);

    }

    public function addPost(){

        $data = [];

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

}