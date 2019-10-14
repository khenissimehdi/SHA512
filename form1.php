<?php
require_once('autoload.include.php') ;
//session_start();
//Session::start();

$p = new WebPage('Authentification') ;

// Production du formulaire de connexion
$p->appendCSS(<<<CSS
    form input {
        width : 4em ;
    }
CSS
) ;
$form = User::loginForm('auth1.php') ;
$logout= User:: logoutForm('logout.php', $submitText = 'OK');
if(!User::isConnected()){

$p->appendContent(<<<HTML
    {$form}
    <p>Pour faire un test : essai/toto
HTML
) ;
}

elseif(User::isConnected()){
    $form = User::loginForm('auth1.php') ;
    $p->appendContent(<<<HTML
        {$logout}
        <h4>Salut {$_SESSION[User::session_key]['user']->firstName()}<h4>
        <p> you are so handsome when you are conected plz never log out </p>

HTML
    ) ;
    }
    

echo $p->toHTML() ;