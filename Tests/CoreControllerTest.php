<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 19/02/2017
 * Time: 16:21
 */
namespace Tests\CoreControllerTest;

require_once 'Application/autoload.php';

use Application\Controller\CoreController;
use Hoa\Dispatcher\Basic;
use Hoa\Router\Http\Http;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class CoreControllerTest extends TestCase {

    /**
     * test web site urls. Return an error if at least one url is not available
     * URLS to test are setted in Application/Config/testsRoutes.yml
     */
    public function testURLS()
    {
        $valuesTestRoutes = Yaml::parse(file_get_contents(__DIR__.'/../Application/Config/testsRoutes.yml'));
        $valuesConfig = Yaml::parse(file_get_contents(__DIR__.'/../Application/Config/routes.yml'));
        foreach ($valuesTestRoutes as $testRoute => $value){
            $dispatcher = new Basic();
            $router = new Http();

            foreach ($valuesConfig as $routeName => $values) {

                $router->get_post($routeName, $values['path'], $values['controller'], $values['action']);

            }

            $router->route((string)$value['path']);
            $response = $dispatcher->dispatch($router);
            $this->assertTrue(is_array($response));
        }

    }
}