<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 08/02/2017
 * Time: 17:35
 */

namespace Application\Controller;

use Application\Manager\FormFactory;

class TagController extends \Hoa\Dispatcher\Kit{

    public function manageTags(){

        $data = [];

        return array('layout' => 'Back/tags.html.twig', 'data' => $data);

    }

    public function createNewTag(){

        $data = [];

        $data['form'] = FormFactory::build('Tag');

        return array('layout' => 'Back/addTag.html.twig', 'data' => $data);

    }

}