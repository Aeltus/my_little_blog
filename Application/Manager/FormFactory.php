<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 03/02/2017
 * Time: 15:53
 */
namespace Application\Manager;

use \ReflectionClass;
use Application\Manager\GetDoctrine;


class FormFactory {

    /**
     * @param string $entity
     *
     * @return string form created
     *
     * Create a form, from an Entity
     */
    public static function build ($entity, $obj = NULL){

        $class = new ReflectionClass('Application\Entity\\'.$entity);
        $x = 0;

        $listParams = FormFactory::getParams($entity);


        foreach ($class->getProperties() as $attribute) {

            $params = $listParams[$x];
            $textareaValue = "";
            
            // set the values in $params from the object values
            if (!empty($obj) && !array_key_exists('externalAttribute', $params)){

                $funcName = 'get'.ucfirst($attribute->getName());
                $value = $obj->$funcName();

                if ($value instanceof \DateTime){
                    $value = "";
                }


                $params['value'] = $value;

                
            }
            

            $field = "<div class=\"form-group\">\n";

            if (array_key_exists('label',$params) && !array_key_exists('externalAttribute', $params)){

                $field .= '<label for="'.$attribute->getName().'">'.$params['label'].'</label>';

            }

            if (array_key_exists('button', $params)){

                $field .= '<a href="Application/FileBrowser/browser.php" target="_blank" class="btn btn-default" id="'.$params['button'].'">Ajouter une image</a>';

            }

            if (array_key_exists('remplacedBy', $params)){

                if ($params['remplacedBy'] == 'pictureFinder'){
                    $field .= "<p id='substitute'>Aucune image n'à encore été choisie</p>";
                }

            }

            if (array_key_exists('externalAttribute', $params)){

                $em = GetDoctrine::getEM();
                $inputs = $em->getRepository('Application\Entity\\'.$params['externalAttribute'])->findAll();
                $field .= '<fieldset class="scheduler-border"><legend class="scheduler-border">'.ucfirst($attribute->getName()).':</legend>';

                foreach ($inputs as $input){


                    unset($params['checked']);
                    $field .= '<label for="'.$attribute->getName().'-'.$input->getId().'">'.$input->getName().'</label>';
                    $field .= "<";

                    // params for tags => get the arrayCollection values
                    if (!empty($obj)){
                        $funcName = "get".ucfirst($attribute->getName());
                        $paramsTags = $obj->$funcName();

                        foreach ($paramsTags as $tag){
                            if ($tag->getName() == $input->getName()){
                                $params['checked'] = "checked";
                            }
                        }
                    }



                    foreach ($params as $param => $value) {

                        // cleanning
                        $param = rtrim($param);
                        $value = rtrim($value);

                        if ($param == "champ") {

                            $type = $value;
                            $field .= $value;
                            $field .= ' name="' . $attribute->getName() . '-'.$input->getId().'" id="' . $attribute->getName() . '-'.$input->getId().'" ';

                        } elseif ($param != "security" && $value != "") {

                            if ($type == "textarea" && $param == "value"){
                                $textareaValue = $value;
                            } else {

                                if (array_key_exists('type', $params)){
                                    if ($params['type'] == "checkbox" && $param == "value"){
                                        if ($value == "1"){
                                            $param = "checked";
                                            $value = "checked";
                                        }
                                    }
                                }

                                $field .= $param . '="' . $value . '" ';
                            }

                        }

                    }

                    if ($type == "input") {
                        $field .= " />";
                    } elseif ($type == "textarea") {
                        $field .= ">";
                        $field .= $textareaValue."</textarea>";
                    }

                }
            $field .= '</fielset>';
            } else {

                $field .= "<";

                foreach ($params as $param => $value) {

                    // cleanning
                    $param = rtrim($param);
                    $value = rtrim($value);

                    if ($param == "champ") {

                        $type = $value;
                        $field .= $value;
                        $field .= ' name="' . $attribute->getName() . '" id="' . $attribute->getName() . '" ';

                    } elseif ($param != "security" && $value != "") {

                        if ($type == "textarea" && $param == "value"){
                            $textareaValue = $value;
                        } else {

                            if (array_key_exists('type', $params)){
                                if ($params['type'] == "checkbox" && $param == "value"){
                                    if ($value == "1"){
                                        $param = "checked";
                                        $value = "checked";
                                    }
                                }
                            }

                            $field .= $param . '="' . $value . '" ';
                        }

                    }

                }

                if ($type == "input") {
                    $field .= " />";
                } elseif ($type == "textarea") {
                    $field .= ">";
                    $field .= $textareaValue."</textarea>";
                }
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
     * @param $object
     * @return string $status
     */
    public static function security($entity, $object){

        $status = NULL;
        $class = new ReflectionClass("Application\Entity\\".$entity);

        if ($class->isInstance($object)){

            $class = new ReflectionClass('Application\Entity\\'.$entity);
            $listParams = FormFactory::getParams($entity);
            $x=0;

            foreach ($class->getProperties() as $attribute) {

                $regex = rtrim($listParams[$x]['security']);

                if (!empty($regex)){

                    $funcName = "get".ucfirst($attribute->getName());
                    $value = $object->$funcName();

                    if (!preg_match($regex, $value)){

                        if (isset($status)){
                            $status .= "|".$attribute->getName();
                        } else {
                            $status = $attribute->getName();
                        }
                    }

                }
                $x++;
            }


        } else {

            $status = "No an instance of".$entity;

        }



    return $status;
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

                    if ($line[6] == "#form") {

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
