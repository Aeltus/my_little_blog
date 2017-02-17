# Welcome on my_little_blog
#### Project number 5 of OpenClassrooms "Developpeur d'application php / Symfony" cursus. 

##### 1. Aim :

The aim of this project, is to create a collaborative blog. Anyone can post an article, post a comment or validate a comment.
Anyone can add picture on the server, and can use it in a post.

The web site, include 2 static pages, "home" and "legal mentions". The otherwise is a simple blog. Themes are from bootstrap
and are customized.

It use some libraries :
 1. Twig
 2. Hoa router
 3. Hoa dispatcher
 4. Swiftmailer
 5. Doctrine ORM
 5. Symfony yaml (for yaml configuration of doctrine)
 6. CKEditor
 7. PHP unit
    
All are included by composer.

##### 2. How to install :

###### 2.1 Download :

Download `my_little_blog` file on gitHub. You can download, or clone it on : https://github.com/Aeltus/my_little_blog

###### 2.2 Installing :

Steps 4, 6, 7 can be replaced by creating your database withs ```./BDD/fixtures.sql``` it set your data base, and put fixtures in.

   1. Put my_little_blog file on root of your web server.
   2. If it is not already done, install composer : https://getcomposer.org/
   3. In root file, open a new terminal windows and type in command line : ```composer install --no-dev``` (next times, to update you wil type : ```composer update --no-dev```)
   4. If not set, create your database.
   5. open ```./Application/Config/Private```, set your paramters in .conf files and rename them in .yml (you can also create a copy of file in .yml)
   6. (Optional) Pointed your URL on : ```./index.php```
   7. In your terminal (in root of your project) type : ```php vendor/doctrine/orm/bin/doctrine orm:schema-tool:create```

Your web site is up !!!

##### 3. Testing :


