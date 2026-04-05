<?php

require_once __DIR__."/../models/Congressiste.php";
require_once __DIR__."/../models/CongressisteRepository.php";
require_once __DIR__."/../core/Database.php";

class AuthController{
    private function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public function login(){
    $error = null;
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new CongressisteRepository($pdo);
    if(isset($_POST['mail']) && isset($_POST['mdp'])){
        $mail = trim($_POST['mail']);
        $password = $_POST['mdp'];
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || $password === '') {
            $error = "Email ou mot de passe invalide.";
            $content = __DIR__ . '/../views/auth/login.php';
            include_once __DIR__.'/../views/layout.php';
            return;
        }
        $connexion = $repo->login(["mail"=>$mail, "mdp"=>$password]);
        //var_dump($connexion);
        //exit;
        //var_dump($connexion);
        if(isset($connexion['success'])){
            $_SESSION['congressiste'] = $connexion['congressiste']; // stocke l’utilisateur complet
            //var_dump($_SESSION);
            //$_SESSION['congressiste_id'] = $connexion['congressiste']['id'];
            //var_dump($_SESSION['participant']);
            header('Location:index.php');
            exit;
        }else{
            $error = $connexion['fail'];
        }

    }
    $content = __DIR__ . '/../views/auth/login.php';
    include_once __DIR__.'/../views/layout.php';

}

public function register(){
    $error = null;
    $success = null;
    $db = new Database();
    $pdo = $db->getConnexion();
    $repo = new CongressisteRepository($pdo);

    if(
        isset($_POST['nom']) &&
        isset($_POST['prenom']) &&
        isset($_POST['adresse']) &&
        isset($_POST['mail']) &&
        isset($_POST['mdp'])
    ){
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $adresse = trim($_POST['adresse']);
        $mail = trim($_POST['mail']);
        $mdp = $_POST['mdp'];

        if ($nom === '' || $prenom === '' || $adresse === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mdp) < 6) {
            $error = "Champs invalides (email valide et mot de passe de 6 caractères minimum).";
            $content = __DIR__ . '/../views/auth/register.php';
            include_once __DIR__.'/../views/layout.php';
            return;
        }

        $result = $repo->register([
            "nom" => $nom,
            "prenom" => $prenom,
            "adresse" => $adresse,
            "mail" => $mail,
            "mdp" => $mdp,
        ]);

        if($result === "exists"){
            $error = "Un compte existe déjà avec cet email.";
        }elseif($result){
            $this->flash('success', "Inscription réussie. Connectez-vous.");
            header("Location: index.php?c=auth&a=login");
            exit;
        }else{
            $error = "L'inscription a échoué.";
        }
    }

    $content = __DIR__ . '/../views/auth/register.php';
    include_once __DIR__.'/../views/layout.php';
}

public function logout(){
    /*
    if(session_destroy())
    {
    unset($_SESSION['participant']);
    // Redirection vers la page de connexion
    header("Location: index.php");
    exit;
    }
    */
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); // démarre la session si elle ne l'est pas déjà
    }

    // Supprimer toutes les variables de session
    $_SESSION = [];

    // Détruire la session
    session_destroy();

    // Redirection vers la page d'accueil
    header("Location: index.php");
    exit;
}
}
?>