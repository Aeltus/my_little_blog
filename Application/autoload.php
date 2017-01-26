<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 25/01/2017
 * Time: 16:31
 */
require_once ('SplClassLoader.php');
require_once(__DIR__.'/../vendor/autoload.php');

$ControllerLoader = new SplClassLoader('Application\Controller', __DIR__.'/../');
$ControllerLoader -> register();

