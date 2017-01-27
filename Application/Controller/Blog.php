<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 17:37
 */

namespace Application\Controller;

class Blog extends \Hoa\Dispatcher\Kit {

    public function Index ( ) {

        $data = [];

        $data['display'] = 'Gordon\'s blog index.';

        return array('layout' => 'index.html.twig', 'data' => $data);

    }

    public function Article ( $id ) {

        echo 'Article n°', $id, '.', "\n";
    }
}

