<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 08/02/2017
 * Time: 20:52
 */

require_once "../autoload.php";

use Application\Manager\FormFactory;

session_start();
$imgToDelete = __DIR__.'/Files/'.$_GET["picture"];

$token = $_GET['token'];

FormFactory::secureCSRF($token, 'DelMedia');


if (unlink($imgToDelete)){
    $_SESSION['messagesSuccess'][] = "L'image à bien été supprimée.";
} else {
    $_SESSION['messagesDanger'][] = "Erreur lors de la suppréssion de l'image.";
}

header("Location: http://".$_SERVER['HTTP_HOST']."/".$_GET['call']);
exit();
