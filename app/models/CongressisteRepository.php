<?php
require_once __DIR__.'/../core/Database.php';
require_once __DIR__.'/Activite.php';

class CongressisteRepository{
    private PDO $pdo;

    public function __construct($db){
        $this->pdo = $db;
    }

    public function login($data):array{
    $sql = "SELECT * FROM congressiste_b WHERE mailCongressiste = :mail";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':mail', $data['mail']);
    $stmt->execute();
    $congressiste = $stmt->fetch(PDO::FETCH_OBJ);
    if ($congressiste && password_verify($data['mdp'], $congressiste->mdp)) {
        return [
            "success" => "Connexion réussie",
            "congressiste" => [
                "id" => $congressiste->IDCongressiste,
                "mail" => $congressiste->mailCongressiste,
                "prenom" => $congressiste->prenomCongressiste,
                "nom" => $congressiste->nomCongressiste,
                "dateinscription" => $congressiste->dateInscription,
                "adresse" => $congressiste->adresseCongressiste
            ]
        ];
    } else {
        return ["fail" => "Connexion échouée"];
    } 
    }

    public function register(array $data): bool|string{
        $check = $this->pdo->prepare("SELECT IDCongressiste FROM congressiste_b WHERE mailCongressiste = :mail");
        $check->bindValue(":mail", $data["mail"]);
        $check->execute();
        if ($check->fetch()) {
            return "exists";
        }

        $sql = "INSERT INTO congressiste_b(nomCongressiste, prenomCongressiste, adresseCongressiste, dateInscription, mailCongressiste, mdp)
                VALUES(:nom, :prenom, :adresse, :dateInscription, :mail, :mdp)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":nom" => $data["nom"],
            ":prenom" => $data["prenom"],
            ":adresse" => $data["adresse"],
            ":dateInscription" => date("Y-m-d"),
            ":mail" => $data["mail"],
            ":mdp" => password_hash($data["mdp"], PASSWORD_BCRYPT),
        ]);
    }

    public function inscrire($data):bool|string{
    // 1. Vérifier si l'inscription existe déjà
    $check = $this->pdo->prepare("SELECT * FROM participer WHERE IDCongressiste = :c AND IDActivite = :a");
    $check->execute([
        ':c' => $data['IDCongressiste'],
        ':a' => $data['IDActivite']
    ]);

    if ($check->fetch()) {
        return "exists"; // déjà inscrit
    }
    //Sinon faire l'inscription
    $sql = "INSERT INTO participer(IDCongressiste, IDActivite) VALUES(?,?)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(1, $data['IDCongressiste']);
    $stmt->bindValue(2, $data['IDActivite']);
    return $stmt->execute();
    }

    public function cancel($data):bool{
    $sql = "DELETE FROM participer WHERE IDCongressiste=:id_congressiste AND IDActivite=:id_activite";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":id_congressiste", $data['IDCongressiste']);
    $stmt->bindValue(":id_activite", $data['IDActivite']);
    return $stmt->execute();
    }

    public function getActivitesByCongressiste(int $idCongressiste): array {
        $ActiviteTab = [];
        $sql="SELECT activite.* FROM activite INNER JOIN participer ON participer.IDActivite = activite.IDActivite INNER JOIN congressiste_b ON participer.IDCongressiste = congressiste_b.IDCongressiste WHERE congressiste_b.IDCongressiste =:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $idCongressiste);
        $stmt->execute();
        $lesActivites = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($lesActivites as $uneActivite){
            $Activite = new Activite($uneActivite->IDActivite, $uneActivite->nomActivite, $uneActivite->descriptionActivite, $uneActivite->dateActivite, $uneActivite->prixActivite);
            $ActiviteTab [] = $Activite;
        }
        return $ActiviteTab;
    }
}
?>