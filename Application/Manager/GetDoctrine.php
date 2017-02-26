<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 09/02/2017
 * Time: 18:39
 */
namespace Application\Manager;

use Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class GetDoctrine {

    public static function getEM(){

        $paths = array(__DIR__."/../Entity");
        $isDevMode = true;

        // the connection configuration
        $dbParams = Yaml::parse(file_get_contents(__Dir__.'/../Config/Private/bdd.yml'));

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

        return EntityManager::create($dbParams, $config);

    }
}
