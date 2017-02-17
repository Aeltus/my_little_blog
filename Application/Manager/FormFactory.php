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

        // for each attribute found in Entity
        foreach ($class->getProperties() as $attribute) {

            // get the params in DocString of the Entity
            $params = FormFactory::getParams($attribute);
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

            // if property "label" found set the label for the field
            if (array_key_exists('label',$params) && !array_key_exists('externalAttribute', $params)){

                $field .= '<label for="'.$attribute->getName().'">'.$params['label'].'</label>';

            }

            // if property "button" found set a button
            if (array_key_exists('button', $params)){

                $field .= '<a href="Application/FileBrowser/browser.php" target="_blank" class="btn btn-default" id="'.$params['button'].'">Ajouter une image</a>';

            }

            // if property "remplacedBy" found, set the substitute of the field
            if (array_key_exists('remplacedBy', $params)){

                if ($params['remplacedBy'] == 'pictureFinder'){
                    $field .= "<p id='substitute'>Aucune image n'à encore été choisie</p>";
                }

            }

            // if property "externalAttribute" found, get the external attributes fields (this is for arrayCollections)
            if (array_key_exists('externalAttribute', $params)){

                $em = GetDoctrine::getEM();

                // get the differents Entities linked to the arrayCollections
                $inputs = $em->getRepository('Application\Entity\\'.$params['externalAttribute'])->findAll();
                $field .= '<fieldset class="scheduler-border"><legend class="scheduler-border">'.ucfirst($attribute->getName()).':</legend>';

                // for each external attribute found, set the differents fields corresponding
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


                    // set the differents fields from params
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

            // if field is not an external attribute
            } else {

                $field .= "<";

                // set the differents fields froms params
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
            $field .= "</div>";
            $fields[] = $field;

        }

        //security token
        $token = uniqid(rand(), true);
        $_SESSION[$entity.'_token'] = $token;
        $_SESSION[$entity.'_token_time'] = time();
        $fields[] = "<input type=hidden name='token' id='token' value='".$token."' />";

    return $fields;
    }

    /**
     * @param string $entity
     * @param $object
     *
     * check secutity param as REGEX. If not corresponding, set $status[] as name of the attribute which cause problems
     * else $status returned empty
     *
     * @return array $status or empty array
     */
    public static function security($entity, $object){

        $status = NULL;
        $class = new ReflectionClass("Application\Entity\\".$entity);
        $status = [];

        if ($class->isInstance($object)){

            foreach ($class->getProperties() as $attribute) {

                $listParams = FormFactory::getParams($attribute);
                if (array_key_exists('security', $listParams)){
                    $regex = rtrim($listParams['security']);
                }

                if (isset($regex) &&!empty($regex)){

                    $funcName = "get".ucfirst($attribute->getName());
                    $value = $object->$funcName();

                    if (is_string($value)){

                        if (!preg_match($regex, $value)){

                            $status[] = $attribute->getName();

                        }

                    }

                }
            }


        } else {

            $status = "No an instance of".$entity;

        }



    return $status;
    }

    /**
     * @param $token
     * @param $entity
     * @return bool or redirect
     */
    public static function secureCSRF($token, $entity){

        $response = false;

        //check if token is in SESSION
        if(isset($_SESSION[$entity.'_token']) && isset($_SESSION[$entity.'_token_time']))
        {
            //If SESSION token == form Token
            if($_SESSION[$entity.'_token'] == $token)
            {
                //User has 15 min to validate the form
                $timestamp_last = time() - (15*60);
                //If token not expired
                if($_SESSION[$entity.'_token_time'] >= $timestamp_last)
                {

                    // if refered adress is from local server
                    $referedAdress = explode("/", $_SERVER['HTTP_REFERER']);
                    if (in_array($_SERVER['HTTP_HOST'], $referedAdress)){
                        $response = true;
                    } else {
                        $_SESSION['messagesDanger'][] = "Le formulaire doit être envoyé par le site :)";
                    }

                } else {

                    $_SESSION['messagesDanger'][] = "Delai expiré";

                }
            } else {

                $_SESSION['messagesDanger'][] = "Le token ne correspond pas.";

            }
        } else {

            $_SESSION['messagesDanger'][] = "Le formulaire doit être envoyé par le site :)";

        }

        // if no problems
        if ($response == true){
            return true;

        // if a problem is set, stop the script.
        } else {
            header("Location: http://".$_SERVER['HTTP_HOST']);
            exit();
        }

    }


    /**
     * @param reflectedClass $attribute
     *
     * @return array
     *
     * get the #form parameters for a form, from phpDoc in an entity
     */
    private static function getParams($attribute){

        // get the docComment
        $docComment = $attribute->getDocComment();
        $listParams = NULL;

        //get lines and clean it
        if (!empty($docComment)){

            $lines = explode("\n", $docComment);

            // get docString lines and clean it
            foreach ($lines as $line){

                $unautorizedChars = ["/**", "/*", "*", "*/", "/"];

                foreach ($unautorizedChars as $unautorizedChar){

                    $line = str_replace($unautorizedChar, "", $line);

                }

                // split params
                if(!empty(trim($line))){

                    $cleannedLine = trim($line);
                    $params = explode(" ", $cleannedLine);
                    $key = trim($params[0]);

                    // take the params for #form and put it in an array
                    $value = trim(str_replace($key, "", $cleannedLine));
                    if ($key == "#form"){

                        $parsedParams = explode("|", $value);
                        foreach ($parsedParams as $parsedParam){
                            $detailParam = explode("=", $parsedParam);

                            // set listParams array as : listparams[param] => value
                            $listParams[$detailParam[0]] = $detailParam[1];
                        }
                    }

                }
            }
        }

        return $listParams;

    }

}
