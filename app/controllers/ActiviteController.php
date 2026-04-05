<?php
require_once __DIR__."/../models/Activite.php";
require_once __DIR__."/../models/ActiviteRepository.php";
require_once __DIR__."/../core/Database.php";
class ActiviteController{
    private function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public function add(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new ActiviteRepository($pdo);
    if(isset($_POST['nom']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['prix'])){
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $date = $_POST['date'];
            $prix = (float)$_POST['prix'];

            if ($nom === '' || $description === '' || $date === '' || $prix < 0) {
                $this->flash('error', "Merci de remplir correctement tous les champs.");
                header("Location: index.php?c=activite&a=add");
                exit;
            }

            $activite = new Activite(0, $nom, $description, $date, (float)$prix);
            $result = $repo->create($activite);
            if($result){
                $this->flash('success', "Activité ajoutée avec succès.");
                header("Location: index.php?c=activite&a=read");
                exit;
            }else{
                $this->flash('error', "L'ajout de l'activité a échoué.");
                header("Location: index.php?c=activite&a=add");
                exit;
            }
        }
        $content = __DIR__ . '/../views/activite/add.php';
        include_once __DIR__.'/../views/layout.php';

    }

    public function read(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new ActiviteRepository($pdo);
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $activite = $repo->findById($id);
        $activites = $activite ? [$activite] : [];
    }else{
        $activites = $repo->findAll();
    }
    // ✅ On définit le contenu à inclure dans le layout
    $content = __DIR__ . '/../views/activite/list.php';

    // ✅ Le layout va inclure $content à l’endroit prévu
    require __DIR__ . '/../views/layout.php';

    }

    public function edit(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new ActiviteRepository($pdo);
    if(!isset($_GET['id'])){
        $this->flash('error', "Aucun ID fourni.");
        header("Location: index.php?c=activite&a=read");
        return;
    }
        $id = (int)$_GET['id'];
        $activite = $repo->findById($id);
        if(!$activite){
            $this->flash('error', "Activité introuvable.");
            header("Location: index.php?c=activite&a=read");
            return;
        }
        if(isset($_POST['nom']) && isset($_POST['description']) && isset($_POST['date']) &&isset($_POST['prix'])){
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $date = $_POST['date'];
            $prix = (float)$_POST['prix'];

            if ($nom === '' || $description === '' || $date === '' || $prix < 0) {
                $this->flash('error', "Merci de remplir correctement tous les champs.");
                header("Location: index.php?c=activite&a=edit&id=" . $id);
                exit;
            }

            $activite->setNom($nom);
            $activite->setDesc($description);
            $activite->setDate($_POST['date']);
            $activite->setPrix($prix);
            if($repo->update($activite)){
            $this->flash('success', "Activité modifiée.");
            header("Location: index.php?c=activite&a=read");
            exit;
            }else{
            $this->flash('error', "La modification a échoué.");
            header("Location: index.php?c=activite&a=edit&id=" . $id);
            exit;
        }
    }
    $content = __DIR__ . '/../views/activite/edit.php';
    include __DIR__ . '/../views/layout.php';
}

public function delete(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new ActiviteRepository($pdo);

    if(!isset($_GET['id'])){
        $this->flash('error', "Aucun ID fourni.");
        header("Location: index.php?c=activite&a=read");
        return;
    }
    $id = (int)$_GET['id'];
    $supprimer = $repo->delete($id);
    if($supprimer){
    $this->flash('success', "Activité supprimée.");
    header("Location: index.php?c=activite&a=read");
    exit;
    }else{
    $this->flash('error', "La suppression n'a pas fonctionné.");
    header("Location: index.php?c=activite&a=read");
    exit;
    }
}

public function getCongressistesByActivite(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new ActiviteRepository($pdo);
    $activites = $repo->findAll();
    if (isset($_POST['activite']) && !empty($_POST['activite'])) {
        $id = (int) $_POST['activite'];
        $Congressistes = $repo->getCongressistesByActivite($id);
    } else {
        $Congressistes = []; // aucune activité sélectionnée
    }
    $content = __DIR__ . '/../views/activite/congressisteTab.php';
        include_once __DIR__.'/../views/layout.php';
    }
    
    }
    


?>