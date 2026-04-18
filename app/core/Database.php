<?php
/**
 * Classe Database
 *
 * Gère la connexion à la base de données MySQL via PDO.
 * Utilisée par tous les repositories pour obtenir une instance PDO.
 *
 * Configuration :
 *   - Hôte       : localhost
 *   - Base        : congresanalim
 *   - Utilisateur : root
 *   - Mot de passe: (vide, configuration WampServer par défaut)
 */
class Database {

    /** @var string Hôte du serveur de base de données */
    private $host = "localhost";

    /** @var string Nom de la base de données */
    private $database_name = "congresanalim";

    /** @var string Nom d'utilisateur */
    private $username = "root";

    /** @var string Mot de passe */
    private $password = "";

    /** @var PDO|null Instance de connexion PDO */
    public $conn;

    /**
     * Retourne une connexion PDO à la base de données.
     * En cas d'échec, affiche le message d'erreur PDO.
     *
     * @return PDO Instance de connexion PDO
     */
    public function getConnexion(): PDO {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->database_name,
                $this->username,
                $this->password
            );
            // Force l'encodage UTF-8 pour tous les échanges avec la base
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connexion BD impossible : " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
