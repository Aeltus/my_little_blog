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


$dispatcher = new Basic();
$router     = new Http();
$router->get('i', '/', 'Application\Controller\Blog', 'index')
       ->get('a', '/article-(?<id>\d+)', 'Application\Controller\Blog', 'article');

$dispatcher->dispatch($router);

