<?php 
 class user{
    const session_key='__user__';
    private $id;//int
    private $lastName;//string
    private $firstName;//string
    private $login;//string
    private $phone;//int

    private function __construct(){}

    public function firstName(){

        return $this->firstName;
    } 

    public function profile($lastName, $firstName, $login, $phone){

        return "<div>Nom: {$this->lastName}</div>
                <div>Prénom: {$this->firstName}</div>
                <div>Login: {$this->login}</div>
                <div>Téléphone: {$this->phone}</div>";
    }

    public function loginForm($action, $submitText = 'OK'){
       
      
       
        $p = <<<HTML
        <form action={$action} method="post">
        Pseudo: <input type="text" name="login" value="" />
         
        Mot de passe: <input type="password" name="pass" value="" />
         
        <input type="submit" name="connexion" value="Connexion" />
    </form>
HTML;
        return $p;
    }

    public function loginFormSHA512($action, $submitText = 'OK')
    {
        Session::start();
        
        $challenge= User:: generateRandomString();
        $_SESSION[User::session_key]['challenge']= $challenge;

        $p=<<<HTML
        <form action="test.html" method="get">
        Pseudo: <input type="text" name="login" id="login" value="" />
         
        Mot de passe: <input type="password" id="pass" name="pass" value="" />
        <input id="code" name="code" type="hidden" value="">
         
        <input type="submit" name="connexion" value="Connexion" onclick="crypto()"/>
    </form>
    
    
    <script>
        function crypto()
        {
            document.getElementById("pass").value=CryptoJS.SHA512(document.getElementById("pass").value);
            document.getElementById("login").value=CryptoJS.SHA512(document.getElementById("login").value);
            document.getElementById("code").value=CryptoJS.SHA512(document.getElementById("pass").value+"{$challenge}"+document.getElementById("login").value);
            
            

        }
    
    </script>
HTML;
       
        return $p;


    }
   




    public function logoutForm($action, $submitText = 'OK'){
       
        $p = <<<HTML
        <form action={$action} method="get">
        logout: 
         
        <input type="submit" name="logout" value="logout" />
    </form>
HTML;
        return $p;
    }






    static public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    

    public function createFromAuth(array $data){

        if(isset($data['login']) && isset($data['pass'])){
            
            $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT id , lastName,firstName,login,phone
            FROM user 
            where login = :login
            and sha512pass = SHA2(:pass, 512) 
SQL
);
        $stmt->setFetchMode (PDO::FETCH_CLASS, User::class);

        $stmt->execute([':login' => $data['login'], ':pass' => $data['pass']]);       

        $user = $stmt->fetch();
      
        }
        if (!isset($user) || $user === false ) {
                      
            throw new AuthentificationException ("Authentification failed");
           
        }
        Session::start();
        if(!isset($_SESSION[User::session_key]))
        {
            $_SESSION[User::session_key]= array();
        }
        $_SESSION[User::session_key]['conected']= true;

        return $user;
        
    

    }


    

    public function createFromAuthSHA512(array $data) {
            Session::start();
            if(!isset($_SESSION[User::session_key]))
            {
                $_SESSION[User::session_key]= array();
            }
            $_SESSION[User::session_key]['conected']= true;
            $challenge=$_SESSION[User::session_key]['challenge'];






            if(isset($data['login']) && isset($data['pass']) && isset($data['code'])){
               
                $stmt = MyPDO::getInstance()->prepare( <<<SQL
                SELECT id , lastName,firstName,login,phone
                FROM user 
                where SHA2(CONCAT(sha2pass,"{$challenge}",SHA2(login,512)),512) = :code
SQL
    );
                $stmt->setFetchMode (PDO::FETCH_CLASS, User::class);
                $stmt->execute([":code" => $data["code"]]);
    
            $user = $stmt->fetch();
          
            }
            if (!isset($user) || $user === false ) {
                          
                throw new AuthentificationException ("Authentification failed");
               
            }
            
           
            
            
            
            
            return $user;
            
        
    
        }
    

















    public function isConnected()
    {
        Session::start();
        if(isset($_SESSION[User::session_key]))
        {
            
            if (isset($_SESSION[User::session_key]['conected']))
            {
                return $_SESSION[User::session_key]['conected'];
            }
        }
        return false;
    }


    public function  logoutIfRequested(array $data)
    {

        if($data['logout'])
        {
            Session::start();
            unset($_SESSION[User::session_key]);

        }

    }

    


    public function saveIntoSession()
    {
        Session::start();
        if (isset($_SESSION[User::session_key]))
        {
                 $_SESSION[User::session_key]['user'] = $this;
           

        }

       
        

    }
    static public function createFromSession()
    {
        Session::start();
        if(!isset($_SESSION[User::session_key]['user']))
        {
            throw new NotInSessionException ("Nothing in the session");
        }
        else
        {
            return $_SESSION[User::session_key]['user'];

        }


    }


 } 