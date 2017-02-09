<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 08/02/2017
 * Time: 22:56
 */

namespace Application\Controller;

use Application\Manager\FormFactory;

class CommentController extends \Hoa\Dispatcher\Kit{

    public function manageComments (){

        $data = [];



        return array('layout' => 'Back/comments.html.twig', 'data' => $data);

    }

}
