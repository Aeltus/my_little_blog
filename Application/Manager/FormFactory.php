<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 03/02/2017
 * Time: 15:53
 */
namespace Application\Manager;

use Application\Entity\Message;
use \ReflectionClass;


class FormFactory {

    /**
     * @param string $entity
     *
     * @return string form created
     *
     * Create a form, from an Entity
     */
    public static function build ($entity){

        $class = new ReflectionClass('Application\Entity\\'.$entity);
        $x = 0;

        $listParams = FormFactory::getParams($entity);


        foreach ($class->getProperties() as $attribute) {

            $params = $listParams[$x];

            $field = "<div class=\"form-group\">\n<";

            foreach ($params as $param => $value){

                if ($param == "champ"){

                    $type = $value;
                    $field .= $value;
                    $field .= ' name="'.$attribute->getName().'" id="'.$attribute->getName().'" ';

                } elseif ($param != "security" && $value != ""){

                    $field .= $param.'="'.$value.'" ';

                }

            }

            if ($type == "input"){
                $field .= "/>";
            } elseif ($type == "textarea"){
                $field .= "></textarea>";
            }

            $field .= "<div class=\"validation\"></div>";
            $field .= "\n</div>";
            $fields[] = $field;
            $x++;

        }
    return $fields;
    }


    /**
     * @param string $entity
     *
     * @return array
     *
     * get the @form parameters for a form, from phpDoc in an entity
     */
    public static function getParams($entity){

        $handle = fopen("Application/Entity/".$entity.".php", "r");

        if ($handle) {

            while (($buffer = fgets($handle, 4096)) !== false) {

                $line = explode(" ", $buffer);

                if (isset($line[6])) {

                    if ($line[6] == "@form") {

                        $data = explode("|", $buffer);
                        $data[0] = substr($data[0], 13);

                        foreach ($data as $param) {
                            $content = explode("=", $param);
                            $params[$content[0]] = $content[1];
                        }

                        $listParams[] = $params;
                        $params = [];

                    }
                }
            }

            if (!feof($handle)) {
                echo "Erreur: fgets() a échoué\n";
            }
            fclose($handle);

        }

        return $listParams;

    }

}
