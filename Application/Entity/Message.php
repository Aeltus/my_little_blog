<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 03/02/2017
 * Time: 15:46
 */

namespace Application\Entity;

class Message {

    /**
     * @var string $name
     * #form champ=input|required=true|type=text|class=form-control|placeholder=Votre nom ici|value=|security=#^[a-zA-Z0-9 ]+$#
     */
    private $name;

    /**
     * @var string $mail
     * #form champ=input|required=true|type=email|class=form-control|placeholder=Votre mail ici|value=|security=#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#
     */
    private $mail;

    /**
     * @var string $subject
     * #form champ=input|required=true|type=text|class=form-control|placeholder=Le sujet ici|value=|security=
     */
    private $subject;

    /**
     * @var string $message
     * #form champ=textarea|required=true|rows=5|class=form-control|placeholder=Votre message ici|value=|security=
     */
    private $message;

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @return string subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


}