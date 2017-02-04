<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 17:37
 */

namespace Application\Controller;

use Application\Manager\FormFactory;


class Core extends \Hoa\Dispatcher\Kit {

    public function index () {

        $data = [];
        $data['form'] = FormFactory::build('Message');

        return array('layout' => 'index.html.twig', 'data' => $data);

    }

    public function contact () {

        $data = [];
        if (isset($_POST['message'])){
            $data['message'] = $_POST['message'];

        }

        $data['form'] = FormFactory::build('Message');

        return array('layout' => 'contact.html.twig', 'data' => $data);
    }

}

