<?php
require_once('autoload.include.php') ;

$p = new WebPage('Authentification') ;

try {
    // Tentative de connexion
    $user = User::createFromAuthSHA512($_REQUEST) ;
    $user->saveIntoSession();
    
    $name= $user->createFromSession();
    
    $logout= User:: logoutForm('logout.php', $submitText = 'OK');
    $p->appendContent(<<<HTML
<div>Salut {$user->firstName()}</div>
<a href="http://khen0002/PHP/PHPMyAdmin/formSHA512.php">hey wanna see a magic trick</a>
     {$logout}
HTML
) ;
}
catch (AuthenticationException $e) {
    // Récuperation de l'exception si connexion échouée
    $p->appendContent("Échec d'authentification&nbsp;: {$e->getMessage()}") ;
}
catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}") ;
}

// Envoi du code HTML au navigateur du client
echo $p->toHTML();