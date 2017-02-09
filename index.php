<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 09:51
 *
 */

require_once ("Application/autoload.php");

use Hoa\Router\Http;
use Hoa\Dispatcher\Basic;
use Hoa\Router\Exception\NotFound;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

session_start();

/* Instantiation of doctrine
 ---------------------------------------------------------------------------------------------------------------------*/

$paths = array(__DIR__."/Application/Entity");
$isDevMode = true;

// the connection configuration
$dbParams = Yaml::parse(file_get_contents('Application/Config/Private/bdd.yml'));

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$em = EntityManager::create($dbParams, $config);

/*--------------------------------------------------------------------------------------------------------------------*/

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

        $router -> get_post($routeName, $values['path'], $values['controller'], $values['action']);

    }

    $response = $dispatcher->dispatch($router);

    // take the messages in $_SESSION to send it to the vue
    $messagesTypes = array('messagesSuccess', 'messagesInfo', 'messagesWarning', 'messagesDanger');

    foreach ($messagesTypes as $type){

        if (isset($_SESSION[$type])){
            $response['data'][$type] = $_SESSION[$type];
            unset($_SESSION[$type]);
    }

    }

    echo $twig->render($response['layout'], $response['data']);

} catch (NotFound $e) {

    echo $twig->render('404.html.twig', array('error' => $e));

}

/*--------------------------------------------------------------------------------------------------------------------*/
