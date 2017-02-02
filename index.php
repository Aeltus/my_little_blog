<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 09:51
 *
 */

require ("Application/autoload.php");

use Hoa\Router\Http;
use Hoa\Dispatcher\Basic;
use Hoa\Router\Exception\NotFound;
use Symfony\Component\Yaml\Yaml;

/* Instantiation of twig
 ---------------------------------------------------------------------------------------------------------------------*/

$loader = new Twig_Loader_Filesystem('Application/View/');
$twig = new Twig_Environment($loader);

/*--------------------------------------------------------------------------------------------------------------------*/

/*Routing / dispatching / render
 ---------------------------------------------------------------------------------------------------------------------*/
$dispatcher = new Basic();
$router     = new Http();

$valuesConfig = Yaml::parse(file_get_contents('Application/Config/routes.yml'));

try {

    foreach ($valuesConfig as $routeName => $values){

        $router -> get($routeName, $values['path'], $values['controller'], $values['action']);

    }

    $response = $dispatcher->dispatch($router);

    echo $twig->render($response['layout'], $response['data']);

} catch (NotFound $e) {

    echo $twig->render('404.html.twig', array('error' => $e));

}

/*--------------------------------------------------------------------------------------------------------------------*/
