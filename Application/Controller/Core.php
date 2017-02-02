<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 17:37
 */

namespace Application\Controller;

class Core extends \Hoa\Dispatcher\Kit {

    public function index () {

        $data = [];

        return array('layout' => 'index.html.twig', 'data' => $data);

    }

    public function contact () {

        $data = [];

        return array('layout' => 'contact.html.twig', 'data' => $data);
    }
}

