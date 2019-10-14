<?php

require_once('autoload.include.php');
//session_start();
//Session::start();
$p = new WebPage('Connexion');


if (User::isConnected() == false){
    
    header("Location: http://khen0002/PHP/PHPMyAdmin/form1.php");
//    header("Location: form1.php");
}
else{
    //$p->toHTML("Titre");
    
    $p->appendContent("<h4>Salut {$_SESSION[User::session_key]['user']->firstName()}<h4><div>you are connected</div>");
}
echo $p->toHTML();
