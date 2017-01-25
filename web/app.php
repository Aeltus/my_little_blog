<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 09:51
 */
require ("../vendor/autoload.php");
require ("../application/SplClassLoader.php");

$ControllerLoader = new SplClassLoader('Application\Controller', '/application/Controller');
$ControllerLoader->register();


use Hoa\Router\Http;
use Hoa\Dispatcher\Basic;
use Application\Controller\Blog;



$dispatcher = new Basic();
$router     = new Http();
$router->get('i', '/', 'blog', 'index')
       ->get('a', '/article-(?<id>\d+)\.html', 'blog', 'article');

$dispatcher->dispatch($router);
