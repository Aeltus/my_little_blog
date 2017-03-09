<?php
/**
 * Created by PhpStorm.
 * User: Davis
 * Date: 07/02/2017
 * Time: 21:01
 */

//get the content of Files directory
echo '<!DOCTYPE html><html lang="fr">';
echo'<link href="../Web/Back/css/styles.css" rel="stylesheet">';
echo '<script type="text/javascript" src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>';
echo '<script src="../Web/Back/js/jquery.js"></script>';
echo '<p><a href="/admin_medias" target="_blank">Ajouter une image sur le serveur</a></p>';
if($folder = opendir('./Files')){

    $autorizedExtentions = array('jpg', 'jpeg', 'png', 'svg', 'gif');
    $files = [];

    while(false !== ($file = readdir($folder))) {

        if($file != '.' && $file != '..'){

            $exploded = explode(".", $file);
            $files[$exploded[0]] = $exploded[1];

            if (in_array(strtolower($exploded[1]), $autorizedExtentions)){

                echo '<a href=""><img src="Files/'.$file.'" id="'.$exploded[0].'" class="browser_img"/></a>';

            }

        }

    }
}


        foreach ($files as $name => $ext){

            $url = 'http://'.$_SERVER['HTTP_HOST'].'/Application/FileBrowser/Files/'.$name.'.'.$ext;

            echo "<script type='text/javascript'>";
            echo "$(function() {";
            echo '$("#'.$name.'").click(function() {
                $("#headerPicture", opener.document).attr("value", "'.$url.'");
                $("#substitute", opener.document).html("<img src=\''.$url.'\' class=\'browser_img\'/>");
                $("#imageFinder", opener.document).text("Modifier l\'image");
                window.close();
                });';
            echo "});";
            echo "</script>";
        }




echo'</html>';
