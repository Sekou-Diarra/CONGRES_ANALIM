<?php
/**
 * Repository CongressisteRepository
 *
 * Contient toutes les requêtes SQL liées à la table 'congressiste_b'
 * et à la table de liaison 'participer'.
 *
 * Méthodes disponibles :
 *   - login()                     : authentifie un congressiste par email et mot de passe
 *   - register()                  : inscrit un nouveau congressiste
 *   - inscrire()                  : inscrit un congressiste à une activité
 *   - cancel()                    : annule l'inscription d'un congressiste à une activité
 *   - getActivitesByCongressiste() : retourne les activités auxquelles un congressiste est inscrit
 */

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Activite.php';

class CongressisteRepository {

    /** @var PDO Instance de connexion PDO */
    private PDO $pdo;

    /**
     * @param PDO $db Instance PDO fournie par Database::getConnexion()
     */
    public function __construct($db) {
        $this->pdo = $db;
    }

    /**
     * Authentifie un congressiste par son email et son mot de passe.
     *
     * Récupère le congressiste en base via son email, puis vérifie
     * le mot de passe avec password_verify (bcrypt).
     *
     * @param array $data Tableau avec les clés 'mail' et 'mdp'
     * @return array ['success' => '...', 'congressiste' => [...]] en cas de succès,
     *               ['fail' => '...'] en cas d'échec
     */
    public function login($data): array {
        $sql  = "SELECT * FROM congressiste_b WHERE mailCongressiste = :mail";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mail', $data['mail']);
        $stmt->execute();
        $congressiste = $stmt->fetch(PDO::FETCH_OBJ);

        if ($congressiste && password_verify($data['mdp'], $congressiste->mdp)) {
            return [
                "success"      => "Connexion réussie",
                "congressiste" => [
                    "id"            => $congressiste->IDCongressiste,
                    "mail"          => $congressiste->mailCongressiste,
                    "prenom"        => $congressiste->prenomCongressiste,
                    "nom"           => $congressiste->nomCongressiste,
                    "dateinscription" => $congressiste->dateInscription,
                    "adresse"       => $congressiste->adresseCongressiste
                ]
            ];
        } else {
            return ["fail" => "Connexion échouée"];
        }
    }

    /**
     * Crée un nouveau compte congressiste.
     *
     * - Vérifie d'abord si l'email est déjà utilisé.
     * - Hache le mot de passe avec PASSWORD_BCRYPT avant insertion.
     * - La date d'inscription est définie automatiquement à la date du jour.
     *
     * @param array $data Tableau avec les clés : nom, prenom, adresse, mail, mdp
     * @return bool|string true si l'inscription a réussi,
     *                     "exists" si l'email est déjà utilisé,
     *                     false en cas d'erreur SQL
     */
    public function register(array $data): bool|string {
        // Vérification de l'unicité de l'email
        $check = $this->pdo->prepare("SELECT IDCongressiste FROM congressiste_b WHERE mailCongressiste = :mail");
        $check->bindValue(":mail", $data["mail"]);
        $check->execute();

        if ($check->fetch()) {
            return "exists";
        }

        $sql  = "INSERT INTO congressiste_b(nomCongressiste, prenomCongressiste, adresseCongressiste, dateInscription, mailCongressiste, mdp)
                 VALUES(:nom, :prenom, :adresse, :dateInscription, :mail, :mdp)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ":nom"             => $data["nom"],
            ":prenom"          => $data["prenom"],
            ":adresse"         => $data["adresse"],
            ":dateInscription" => date("Y-m-d"),
            ":mail"            => $data["mail"],
            ":mdp"             => password_hash($data["mdp"], PASSWORD_BCRYPT),
        ]);
    }

    /**
     * Inscrit un congressiste à une activité.
     *
     * - Vérifie d'abord si l'inscription existe déjà (doublons interdits).
     * - Insère une ligne dans la table de liaison 'participer'.
     *
     * @param array $data Tableau avec les clés 'IDCongressiste' et 'IDActivite'
     * @return bool|string true si l'inscription a réussi,
     *                     "exists" si l'inscription existe déjà,
     *                     false en cas d'erreur SQL
     */
    public function inscrire($data): bool|string {
        // Vérification d'une éventuelle double inscription
        $check = $this->pdo->prepare("SELECT * FROM participer WHERE IDCongressiste = :c AND IDActivite = :a");
        $check->execute([
            ':c' => $data['IDCongressiste'],
            ':a' => $data['IDActivite']
        ]);

        if ($check->fetch()) {
            return "exists";
        }

        // Insertion de l'inscription dans la table de liaison
        $sql  = "INSERT INTO participer(IDCongressiste, IDActivite) VALUES(?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $data['IDCongressiste']);
        $stmt->bindValue(2, $data['IDActivite']);
        return $stmt->execute();
    }

    /**
     * Annule l'inscription d'un congressiste à une activité.
     *
     * Supprime la ligne correspondante dans la table 'participer'.
     *
     * @param array $data Tableau avec les clés 'IDCongressiste' et 'IDActivite'
     * @return bool true si la suppression a réussi, false sinon
     */
    public function cancel($data): bool {
        $sql  = "DELETE FROM participer WHERE IDCongressiste = :id_congressiste AND IDActivite = :id_activite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id_congressiste", $data['IDCongressiste']);
        $stmt->bindValue(":id_activite",     $data['IDActivite']);
        return $stmt->execute();
    }

    /**
     * Retourne la liste des activités auxquelles un congressiste est inscrit.
     *
     * Effectue une jointure entre 'activite', 'participer' et 'congressiste_b'
     * pour récupérer uniquement les activités du congressiste donné.
     *
     * @param int $idCongressiste Identifiant du congressiste
     * @return Activite[] Tableau d'objets Activite
     */
    public function getActivitesByCongressiste(int $idCongressiste): array {
        $ActiviteTab = [];
        $sql = "SELECT activite.*
                FROM activite
                INNER JOIN participer     ON participer.IDActivite    = activite.IDActivite
                INNER JOIN congressiste_b ON participer.IDCongressiste = congressiste_b.IDCongressiste
                WHERE congressiste_b.IDCongressiste = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $idCongressiste);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($rows as $row) {
            $ActiviteTab[] = new Activite(
                $row->IDActivite,
                $row->nomActivite,
                $row->descriptionActivite,
                $row->dateActivite,
                $row->prixActivite
            );
        }

        return $ActiviteTab;
    }
}
?>
