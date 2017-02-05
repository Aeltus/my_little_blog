<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 24/01/2017
 * Time: 17:37
 */

namespace Application\Controller;

use Application\Entity\Message;
use Application\Manager\FormFactory;
use Symfony\Component\Yaml\Yaml;


class Core extends \Hoa\Dispatcher\Kit {

    /**
     * index web page
     *
     * @return array
     */
    public function index () {

        $data = [];
        $data['form'] = FormFactory::build('Message');

        return array('layout' => 'index.html.twig', 'data' => $data);

    }


    /**
     * Contact web page, get and send a message
     *
     * @return array
     */
    public function contact () {

/*======================================================================================================================
*                                                                                                                      *
*                                         verifications                                                                *
*                                                                                                                      *
*=====================================================================================================================*/

        $data = [];
        if (!empty($_POST)) {

            $message = new Message();

            foreach ($_POST as $key => $value) {
                $funcName = "set" . ucfirst($key);
                $message->$funcName($value);
            }

            $security = FormFactory::security('Message', $message);


            if (isset($security)) {

                $errorMessage = [];
                $errorMessage[] = "Le formulaire est mal remplis.";

                $errors = explode("|", $security);

                foreach ($errors as $error) {
                    if ($error == "name") {
                        $errorMessage[] = "Entrez un nom valide composé uniquement de lettres, chiffres et espaces.";
                    }
                    if ($error == "mail") {
                        $errorMessage[] = "Entrez un mail valide.";
                    }
                }

                $data['messagesWarning'] = $errorMessage;
            } else {

                $data['messagesSuccess'] = array("Votre message à bien été envoyé");
            }
        }

/*====================================================================================================================*/

/*======================================================================================================================
*                                                                                                                      *
*                                           Mail sender                                                                *
*                                                                                                                      *
*=====================================================================================================================*/
if (isset($message) && !isset($security)){

    // transport creating
    $valuesConfig = Yaml::parse(file_get_contents('Application/Config/Private/mail.yml'));

    $transport = \Swift_SmtpTransport::newInstance($valuesConfig['mail']['server'], $valuesConfig['mail']['port'], $valuesConfig['mail']['security'])
        ->setUsername($valuesConfig['mail']['id'])
        ->setPassword($valuesConfig['mail']['password'])
    ;
    $mailer = \Swift_Mailer::newInstance($transport);

    // message creating
    $email = \Swift_Message::newInstance()

        ->setSubject($message->getSubject())

        ->setFrom(array($message->getMail() => $message->getName()))

        ->setTo(array('david.danjard@gmail.com' => 'David DANJARD'))

        ->setBody($message->getMessage())

    ;

    // sending
    if (!$mailer->send($email, $failures))
    {
        $data['messagesDanger'] = $failures;
    }

}

/*====================================================================================================================*/

/*======================================================================================================================
*                                                                                                                      *
*                                       Form creating + return                                                         *
*                                                                                                                      *
*=====================================================================================================================*/
        if (isset($message) && isset($security)){
            $data['form'] = FormFactory::build('Message', $message);
        } else {
            $data['form'] = FormFactory::build('Message');
        }


        return array('layout' => 'contact.html.twig', 'data' => $data);

/*====================================================================================================================*/
    }

}

