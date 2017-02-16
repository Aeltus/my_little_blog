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


class CoreController extends \Hoa\Dispatcher\Kit {

    /**
     * index web page
     *
     * @return array
     */
    public function index () {

        $data = [];

        if (!isset($_SESSION['cookies'])){
            $data['messageCookies'] = "En poursuivant la navigation sur ce site, vous acceptez l'utilisation des cookies.";
            $_SESSION['cookies'] = TRUE;
        }

        //security token
        $token = uniqid(rand(), true);
        $_SESSION['Message_token'] = $token;
        $_SESSION['Message_token_time'] = time();

        $data['token'] = $token;

        $data['form'] = FormFactory::build('Message');

        return array('layout' => 'Front/index.html.twig', 'data' => $data);

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

            FormFactory::secureCSRF($_POST['token'], 'Message');

            foreach ($_POST as $key => $value) {
                if($key != 'token'){
                    $funcName = "set" . ucfirst($key);
                    $message->$funcName($value);
                }
            }

            $security = FormFactory::security('Message', $message);


            if (!empty($security)) {

                $_SESSION['messagesWarning'][] = "Le formulaire est mal remplis.";

                foreach ($security as $error) {
                    if ($error == "name") {
                        $_SESSION['messagesWarning'][] = "Entrez un nom valide composé uniquement de lettres, chiffres et espaces.";
                    }
                    if ($error == "mail") {
                        $_SESSION['messagesWarning'][] = "Entrez un mail valide.";
                    }
                }


            } else {

                $_SESSION['messagesSuccess'][] = "Votre message à bien été envoyé";
            }
        }

/*====================================================================================================================*/

/*======================================================================================================================
*                                                                                                                      *
*                                           Mail sender                                                                *
*                                                                                                                      *
*=====================================================================================================================*/
if (isset($message) && empty($security)){

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

        ->setTo(array($valuesConfig['mail']['sendTo'] => $valuesConfig['mail']['sendToName']))

        ->setBody($message->getMessage())

    ;

    // sending
    if (!$mailer->send($email, $failures))
    {
        $data['messagesDanger'][] = $failures;
    }

}

/*====================================================================================================================*/

/*======================================================================================================================
*                                                                                                                      *
*                                       Form creating + return                                                         *
*                                                                                                                      *
*=====================================================================================================================*/
        //security token
        $token = uniqid(rand(), true);
        $_SESSION['Message_token'] = $token;
        $_SESSION['Message_token_time'] = time();

        $data['token'] = $token;

        if (isset($message) && !empty($security)){
            $data['form'] = FormFactory::build('Message', $message);
        } else {
            $data['form'] = FormFactory::build('Message');
        }


        return array('layout' => 'Front/contact.html.twig', 'data' => $data);

/*====================================================================================================================*/
    }

}

