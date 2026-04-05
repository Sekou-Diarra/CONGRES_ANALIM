<?php
require_once __DIR__.'/../core/Database.php';
require_once __DIR__.'/Activite.php';
require_once __DIR__.'/Congressiste.php';

class ActiviteRepository{
    private PDO $pdo;

    public function __construct($db){
        $this->pdo = $db;
    }

    public function create(Activite $activite):bool{
        $sql="INSERT INTO activite(nomActivite, descriptionActivite, dateActivite, prixActivite) VALUES(?,?,?,?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $activite->getNom());
        $stmt->bindValue(2, $activite->getDesc());
        $stmt->bindValue(3, $activite->getDate());
        $stmt->bindValue(4, $activite->getPrix());
        return $stmt->execute();
    }

    public function findAll():array{
        $sql="SELECT * FROM activite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $Activites = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ActivityTab = [];
        foreach($Activites as $uneActivite){
            $activite = new Activite($uneActivite->IDActivite, $uneActivite->nomActivite, $uneActivite->descriptionActivite, $uneActivite->dateActivite, $uneActivite->prixActivite);
            $ActivityTab [] = $activite;
        }
        return $ActivityTab;
    }

    public function findById($id):?Activite{
        $sql="SELECT * FROM activite WHERE IDActivite=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $uneActivite = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$uneActivite) {
            return null;
        }
        return new Activite($uneActivite->IDActivite, $uneActivite->nomActivite, $uneActivite->descriptionActivite, $uneActivite->dateActivite, $uneActivite->prixActivite);
    }

    public function update(Activite $activite):bool{
        $sql="UPDATE activite SET nomActivite =:nom, descriptionActivite =:description, dateActivite=:date, prixActivite=:prix WHERE IDActivite=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":nom", $activite->getNom());
        $stmt->bindValue(":description", $activite->getDesc());
        $stmt->bindValue(":date", $activite->getDate());
        $stmt->bindValue(":prix", $activite->getPrix());
        $stmt->bindValue(":id", $activite->getId());
        return $stmt->execute();
    }

    public function delete($id):bool{
        try {
            $this->pdo->beginTransaction();

            // Nettoie d'abord les inscriptions liées pour éviter les incohérences
            // si la base n'est pas encore en ON DELETE CASCADE.
            $sqlParticiper = "DELETE FROM participer WHERE IDActivite = :id";
            $stmtParticiper = $this->pdo->prepare($sqlParticiper);
            $stmtParticiper->bindValue(":id", $id);
            $stmtParticiper->execute();

            $sql="DELETE FROM activite WHERE IDActivite=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $isDeleted = $stmt->execute();

            $this->pdo->commit();
            return $isDeleted;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function getCongressistesByActivite(int $idActivite): array {
        $CongressisteTab = [];
        $sql="SELECT congressiste_b.* FROM activite INNER JOIN participer ON participer.IDActivite = activite.IDActivite INNER JOIN congressiste_b ON participer.IDCongressiste = congressiste_b.IDCongressiste WHERE activite.IDActivite =:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $idActivite);
        $stmt->execute();
        $lesCongressistes = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($lesCongressistes as $unCongressiste){
            $Congressiste = new Congressiste($unCongressiste->IDCongressiste, $unCongressiste->nomCongressiste, $unCongressiste->prenomCongressiste, $unCongressiste->adresseCongressiste, $unCongressiste->dateInscription, $unCongressiste->mailCongressiste, $unCongressiste->mdp);
            $CongressisteTab [] = $Congressiste;
        }
        return $CongressisteTab;
    }
}
?>