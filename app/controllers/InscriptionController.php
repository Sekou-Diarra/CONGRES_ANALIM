<?php
require_once __DIR__."/../models/Congressiste.php";
require_once __DIR__."/../models/CongressisteRepository.php";
require_once __DIR__."/../core/Database.php";

class InscriptionController {
    private function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /** Admin (organisateur) : id 1 — pas d'inscription aux activités comme un participant */
    private function isAdmin(): bool {
        return isset($_SESSION['congressiste']['id'])
            && (int)$_SESSION['congressiste']['id'] === 1;
    }

    public function inscrire() {
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new CongressisteRepository($pdo);
    if ($this->isAdmin()) {
        $this->flash('error', "L'organisateur ne peut pas s'inscrire aux activités en tant que participant.");
        header("Location: index.php?c=activite&a=read");
        exit;
    }
    if(isset($_SESSION['congressiste']) && isset($_POST['IDActivite'])){
        $inscription = $repo->inscrire([
            "IDCongressiste" => (int)$_SESSION['congressiste']['id'],
            "IDActivite" => (int)$_POST['IDActivite']
        ]);
        if($inscription === "exists"){
            $this->flash('error', "Vous êtes déjà inscrit à cette activité.");
        }elseif($inscription){
            $this->flash('success', "Inscription réussie.");
        }else{
            $this->flash('error', "Inscription échouée.");
        }
    } else {
        $this->flash('error', "Vous devez vous connecter pour vous inscrire.");
    }
    header("Location: index.php?c=activite&a=read");
    exit;
    }

    public function cancel() {
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new CongressisteRepository($pdo);
    if ($this->isAdmin()) {
        $this->flash('error', "L'organisateur ne gère pas d'inscriptions personnelles aux activités.");
        header("Location: index.php?c=activite&a=read");
        exit;
    }
    if(isset($_SESSION['congressiste']) && isset($_POST['IDActivite'])){
        $inscription = $repo->cancel([
            "IDCongressiste" => (int)$_SESSION['congressiste']['id'],
            "IDActivite" => (int)$_POST['IDActivite']
        ]);
        if($inscription){
            $this->flash('success', "Inscription annulée.");
        }else{
            $this->flash('error', "L'inscription n'a pas pu être annulée.");
        }
    } else {
        $this->flash('error', "Vous devez vous connecter pour annuler.");
    }
    header("Location: index.php?c=activite&a=read");
    exit;
    }

    public function getActivitesByCongressiste(){
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new CongressisteRepository($pdo);
    if ($this->isAdmin()) {
        $this->flash('error', "La page « mes inscriptions » est réservée aux participants.");
        header("Location: index.php?c=activite&a=read");
        exit;
    }
    if(!isset($_SESSION['congressiste']['id'])){
        $this->flash('error', "Vous devez vous connecter d'abord.");
        header("Location: index.php?c=auth&a=login");
        exit;
    }
    $id = (int)$_SESSION['congressiste']['id'];
    $Activites = $repo->getActivitesByCongressiste($id);
    $content = __DIR__ . '/../views/activite/congressisteActivite.php';
        include_once __DIR__.'/../views/layout.php';
    }
}
?>