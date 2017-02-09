<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 06/02/2017
 * Time: 17:32
 */

require_once ("Application/autoload.php");

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


/* Instantiation of doctrine
 ---------------------------------------------------------------------------------------------------------------------*/

$paths = array("Application/Entity");
$isDevMode = true;

// the connection configuration
$dbParams = Yaml::parse(file_get_contents('Application/Config/Private/bdd.yml'));


$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$em = EntityManager::create($dbParams, $config);

return ConsoleRunner::createHelperSet($em);

/*--------------------------------------------------------------------------------------------------------------------*/
