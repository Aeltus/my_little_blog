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
use Application\Manager\GetDoctrine;


/* Instantiation of doctrine
 ---------------------------------------------------------------------------------------------------------------------*/

$em = GetDoctrine::getEM();

return ConsoleRunner::createHelperSet($em);

/*--------------------------------------------------------------------------------------------------------------------*/
