<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 07/02/2017
 * Time: 21:02
 */
session_start();


function upload($index,$destination,$maxsize=FALSE,$extensions=FALSE, $ext){

    //Test1: extension
    if ($extensions !== FALSE AND !in_array($ext,$extensions)){
        return "Erreur : Type de fichier non accepté (jpg | png | svg | gif)";
    }

    //Test2: max file size
    if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize){
        return "Erreur : Taille maximum dépassée (1.5 Mo au max)";
    }

    //Test3: file correctly uploaded
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0){
        return "Erreur : Erreur lors du transfet du fichier";
    }

    //moving
    if (move_uploaded_file($_FILES[$index]['tmp_name'],$destination)){
        return true;
    } else {
        return "Erreur : Erreur lors de l'enregistrement du fichier sur le serveur";
    }

}

$ext = substr(strrchr($_FILES['upload']['name'],'.'),1);
$url= "Files/".time().".".$ext;


$result = upload('upload',$url,1050000, array('png','gif','jpg','jpeg', 'svg'), $ext);


if (isset($_GET['call'])){
    if ($result === true){
        $_SESSION['messagesSuccess'][] = "Upload réussi.";
    } else {
        $_SESSION['messagesDanger'][] = $result;
    }
    header("Location: http://".$_SERVER['HTTP_HOST']."/".$_GET['call']);
    exit();
} else {
    if ($result === true){
        echo 'Upload réussi.';
    } else {
        echo $result;
    }

}
