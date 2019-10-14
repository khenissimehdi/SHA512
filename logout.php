<?php
require_once('autoload.include.php') ;

$p = new WebPage('logout') ;

User::logoutIfRequested($_REQUEST);

$p->appendContent(<<<HTML
<div>you logged out i wish you a good day </div>
<a href="http://khen0002/PHP/PHPMyAdmin/form1.php">get back her boy</a>
     
HTML
) ;


echo $p->toHTML();