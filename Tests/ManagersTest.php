<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 19/02/2017
 * Time: 18:21
 */

namespace Tests\ManagersTest;

require_once 'Application/autoload.php';

use Application\Manager\FormFactory;
use Application\Manager\GetDoctrine;
use PHPUnit\Framework\TestCase;


class ManagersTest extends TestCase {

    /**
     * test if formBuilder return an array (or an error) for used Entities
     */
    public function testFormBuilder(){

        $entities = ['BlogPost', 'Comment', 'Message', 'Tag'];
        foreach ($entities as $entity){
            $testForm = FormFactory::build($entity);
            $this->assertTrue(is_array($testForm));
        }

    }

    /**
     * test if entity manager return an array as expected
     */
    public function testEntityManager(){
        $em = GetDoctrine::getEM();
        $this->assertTrue(is_object($em));
    }

}
