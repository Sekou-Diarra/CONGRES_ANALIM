<?php
/**
 * Repository ActiviteRepository
 *
 * Contient toutes les requêtes SQL liées à la table 'activite'
 * et à la table de liaison 'participer'.
 *
 * Méthodes disponibles :
 *   - create()                    : insère une nouvelle activité
 *   - findAll()                   : retourne toutes les activités
 *   - findById()                  : retourne une activité par son ID
 *   - update()                    : met à jour une activité existante
 *   - delete()                    : supprime une activité et ses inscriptions associées
 *   - getCongressistesByActivite() : retourne les congressistes inscrits à une activité
 */

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Activite.php';
require_once __DIR__ . '/Congressiste.php';

class ActiviteRepository {

    /** @var PDO Instance de connexion PDO */
    private PDO $pdo;

    /**
     * @param PDO $db Instance PDO fournie par Database::getConnexion()
     */
    public function __construct($db) {
        $this->pdo = $db;
    }

    /**
     * Insère une nouvelle activité en base de données.
     *
     * @param Activite $activite L'entité à persister
     * @return bool true si l'insertion a réussi, false sinon
     */
    public function create(Activite $activite): bool {
        $sql  = "INSERT INTO activite(nomActivite, descriptionActivite, dateActivite, prixActivite) VALUES(?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $activite->getNom());
        $stmt->bindValue(2, $activite->getDesc());
        $stmt->bindValue(3, $activite->getDate());
        $stmt->bindValue(4, $activite->getPrix());
        return $stmt->execute();
    }

    /**
     * Retourne toutes les activités de la base de données.
     *
     * @return Activite[] Tableau d'objets Activite
     */
    public function findAll(): array {
        $sql  = "SELECT * FROM activite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows        = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ActivityTab = [];

        foreach ($rows as $row) {
            $ActivityTab[] = new Activite(
                $row->IDActivite,
                $row->nomActivite,
                $row->descriptionActivite,
                $row->dateActivite,
                $row->prixActivite
            );
        }

        return $ActivityTab;
    }

    /**
     * Retourne une activité par son identifiant, ou null si elle n'existe pas.
     *
     * @param int $id Identifiant de l'activité
     * @return Activite|null L'entité trouvée ou null
     */
    public function findById($id): ?Activite {
        $sql  = "SELECT * FROM activite WHERE IDActivite = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$row) {
            return null;
        }

        return new Activite(
            $row->IDActivite,
            $row->nomActivite,
            $row->descriptionActivite,
            $row->dateActivite,
            $row->prixActivite
        );
    }

    /**
     * Met à jour une activité existante en base de données.
     *
     * @param Activite $activite L'entité avec les nouvelles valeurs
     * @return bool true si la mise à jour a réussi, false sinon
     */
    public function update(Activite $activite): bool {
        $sql  = "UPDATE activite SET nomActivite = :nom, descriptionActivite = :description, dateActivite = :date, prixActivite = :prix WHERE IDActivite = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nom",         $activite->getNom());
        $stmt->bindValue(":description", $activite->getDesc());
        $stmt->bindValue(":date",        $activite->getDate());
        $stmt->bindValue(":prix",        $activite->getPrix());
        $stmt->bindValue(":id",          $activite->getId());
        return $stmt->execute();
    }

    /**
     * Supprime une activité et toutes ses inscriptions associées.
     *
     * La suppression est effectuée dans une transaction pour garantir
     * la cohérence des données : on supprime d'abord les lignes dans
     * 'participer', puis l'activité elle-même.
     * En cas d'erreur, un rollback est effectué automatiquement.
     *
     * @param int $id Identifiant de l'activité à supprimer
     * @return bool true si la suppression a réussi, false sinon
     */
    public function delete($id): bool {
        try {
            $this->pdo->beginTransaction();

            // Suppression des inscriptions liées à l'activité
            $sqlParticiper = "DELETE FROM participer WHERE IDActivite = :id";
            $stmtParticiper = $this->pdo->prepare($sqlParticiper);
            $stmtParticiper->bindValue(":id", $id);
            $stmtParticiper->execute();

            // Suppression de l'activité
            $sql  = "DELETE FROM activite WHERE IDActivite = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $isDeleted = $stmt->execute();

            $this->pdo->commit();
            return $isDeleted;

        } catch (Throwable $e) {
            // Annule toutes les suppressions en cas d'erreur
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    /**
     * Retourne la liste des congressistes inscrits à une activité donnée.
     *
     * Effectue une jointure entre les tables 'activite', 'participer'
     * et 'congressiste_b' pour récupérer les participants de l'activité.
     *
     * @param int $idActivite Identifiant de l'activité
     * @return Congressiste[] Tableau d'objets Congressiste
     */
    public function getCongressistesByActivite(int $idActivite): array {
        $CongressisteTab = [];
        $sql = "SELECT congressiste_b.*
                FROM activite
                INNER JOIN participer       ON participer.IDActivite    = activite.IDActivite
                INNER JOIN congressiste_b   ON participer.IDCongressiste = congressiste_b.IDCongressiste
                WHERE activite.IDActivite = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $idActivite);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($rows as $row) {
            $CongressisteTab[] = new Congressiste(
                $row->IDCongressiste,
                $row->nomCongressiste,
                $row->prenomCongressiste,
                $row->adresseCongressiste,
                $row->dateInscription,
                $row->mailCongressiste,
                $row->mdp
            );
        }

        return $CongressisteTab;
    }
}
?>
