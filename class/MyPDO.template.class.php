<?php

/**
 * Classe permettant de retourner une instance unique de PDO
 * afin de ne pas multiplier les connexions au serveur de base de données.
 */
final class MyPDO
{
    /**
     * instance unique de PDO.
     *
     * @var PDO $PDOInstance
     */
    private static $PDOInstance = null;

    /**
     *  DSN pour la connexion BD.
     *
     * @var string $DSN
     */
    private static $DSN = null;
    /**
     * nom d'utilisateur pour la connexion BD.
     *
     * @var string $username
     */
    private static $username = null;
    /**
     * mot de passe pour la connexion BD.
     *
     * @var string $password

     */
    private static $password = null;
    /**
     * options du pilote BD.
     *
     * @var array $driverOptions
     */
    private static $driverOptions = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );

    /**
     * Constructeur privé
     * (juste pour vous passer l'envie d'instancier des objets MyPDO).
     */
    private function __construct()
    {
    }

    /**
     * Point d'accès à l'instance unique.
     * L'instance est créée au premier appel
     * et réutilisée aux appels suivants.
     *
     * @throws Exception si la configuration n'a pas été effectuée
     *
     * @return PDO instance unique
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$PDOInstance)) {
            if (self::hasConfiguration()) {
                self::$PDOInstance = new PDO(self::$DSN, self::$username, self::$password, self::$driverOptions);
            } else {
                throw new Exception(__CLASS__.': Configuration not set');
            }
        }

        return self::$PDOInstance;
    }

    /**
     * Fixer la configuration de la connexion à la BD.
     *
     * @param string $dsn            DNS pour la connexion BD
     * @param string $username       utilisateur pour la connexion BD
     * @param string $password       mot de passe pour la connexion BD
     * @param array  $driver_options options du pilote BD
     */
    public static function setConfiguration(
        string $dsn,
        string $username = '',
        string $password = '',
        array $driver_options = array()
    ): void {
        self::$DSN = $dsn;
        self::$username = $username;
        self::$password = $password;
        self::$driverOptions = $driver_options + self::$driverOptions;
    }

    /**
     * Vérifier si la configuration de la connexion à la BD a été effectuée.
     *
     * @return bool
     */
    private static function hasConfiguration(): bool
    {
        return self::$DSN !== null;
    }
}

/* Exemple de configuration et d'utilisation
require_once 'MyPDO.template.class.php';

MyPDO::setConfiguration('mysql:host=mysql;dbname=DBNAME;charset=utf8', 'USERNAME', 'PASSWORD');

$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT id, name
    FROM artist
    ORDER BY name
SQL
);

$stmt->execute();

while (($ligne = $stmt->fetch()) !== false) {
    echo "<p>{$ligne['name']}\n";
}
*/
