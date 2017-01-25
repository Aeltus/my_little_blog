<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 17:37
 */

namespace Application\Controller;

class Blog extends \Hoa\Dispatcher\Kit {

    public function IndexAction ( ) {

        echo 'Gordon\'s blog index.', "\n";
    }

    public function ArticleAction ( $id ) {

        echo 'Article n°', $id, '.', "\n";
    }
}

