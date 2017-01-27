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

        echo 'Gordon\'s blog index.', "\n";
    }

    public function Article ( $id ) {

        echo 'Article n°', $id, '.', "\n";
    }
}

