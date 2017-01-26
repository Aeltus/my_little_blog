<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 09:51
 */
require ("../autoload.php");


use Hoa\Router\Http;
use Hoa\Dispatcher\Basic;
use Application\Controller\Blog;


$dispatcher = new Basic();
$router     = new Http();
$router->get('i', '/', 'Blog', 'index');

$dispatcher->dispatch($router);

