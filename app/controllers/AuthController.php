<?php
/**
 * Contrôleur AuthController
 *
 * Gère l'authentification des congressistes :
 *   - login()    : connexion via email + mot de passe
 *   - register() : création d'un nouveau compte congressiste
 *   - logout()   : destruction de la session et redirection
 */

require_once __DIR__ . "/../models/Congressiste.php";
require_once __DIR__ . "/../models/CongressisteRepository.php";
require_once __DIR__ . "/../core/Database.php";

class AuthController {

    /**
     * Stocke un message flash en session pour l'afficher à la prochaine page.
     *
     * @param string $type    Type du message : 'success' ou 'error'
     * @param string $message Contenu du message à afficher
     */
    private function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message
        ];
    }

    /**
     * Gère la connexion d'un congressiste.
     *
     * - Si le formulaire est soumis (POST), valide l'email et le mot de passe.
     * - En cas de succès, stocke les données du congressiste en session
     *   et redirige vers la page d'accueil.
     * - En cas d'échec, affiche le formulaire avec un message d'erreur.
     */
    public function login() {
        $error = null;
        $db    = new Database();
        $pdo   = $db->getConnexion();
        $repo  = new CongressisteRepository($pdo);

        if (isset($_POST['mail']) && isset($_POST['mdp'])) {
            $mail     = trim($_POST['mail']);
            $password = $_POST['mdp'];

            // Validation basique : email valide et mot de passe non vide
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || $password === '') {
                $error   = "Email ou mot de passe invalide.";
                $content = __DIR__ . '/../views/auth/login.php';
                include_once __DIR__ . '/../views/layout.php';
                return;
            }

            $connexion = $repo->login(["mail" => $mail, "mdp" => $password]);

            if (isset($connexion['success'])) {
                // Stocke les informations du congressiste en session
                $_SESSION['congressiste'] = $connexion['congressiste'];
                header('Location:index.php');
                exit;
            } else {
                $error = $connexion['fail'];
            }
        }

        $content = __DIR__ . '/../views/auth/login.php';
        include_once __DIR__ . '/../views/layout.php';
    }

    /**
     * Gère l'inscription d'un nouveau congressiste.
     *
     * - Vérifie que tous les champs sont remplis et valides.
     * - Contrôle que l'email n'est pas déjà utilisé.
     * - En cas de succès, redirige vers la page de connexion avec un message flash.
     * - Mot de passe : minimum 6 caractères (hashé en base via bcrypt).
     */
    public function register() {
        $error   = null;
        $success = null;
        $db      = new Database();
        $pdo     = $db->getConnexion();
        $repo    = new CongressisteRepository($pdo);

        if (
            isset($_POST['nom'])     &&
            isset($_POST['prenom'])  &&
            isset($_POST['adresse']) &&
            isset($_POST['mail'])    &&
            isset($_POST['mdp'])
        ) {
            $nom     = trim($_POST['nom']);
            $prenom  = trim($_POST['prenom']);
            $adresse = trim($_POST['adresse']);
            $mail    = trim($_POST['mail']);
            $mdp     = $_POST['mdp'];

            // Validation : tous les champs requis, email valide, mdp >= 6 caractères
            if ($nom === '' || $prenom === '' || $adresse === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL) || strlen($mdp) < 6) {
                $error   = "Champs invalides (email valide et mot de passe de 6 caractères minimum).";
                $content = __DIR__ . '/../views/auth/register.php';
                include_once __DIR__ . '/../views/layout.php';
                return;
            }

            $result = $repo->register([
                "nom"     => $nom,
                "prenom"  => $prenom,
                "adresse" => $adresse,
                "mail"    => $mail,
                "mdp"     => $mdp,
            ]);

            if ($result === "exists") {
                $error = "Un compte existe déjà avec cet email.";
            } elseif ($result) {
                $this->flash('success', "Inscription réussie. Connectez-vous.");
                header("Location: index.php?c=auth&a=login");
                exit;
            } else {
                $error = "L'inscription a échoué.";
            }
        }

        $content = __DIR__ . '/../views/auth/register.php';
        include_once __DIR__ . '/../views/layout.php';
    }

    /**
     * Déconnecte le congressiste en cours.
     *
     * - Vide toutes les variables de session.
     * - Détruit la session.
     * - Redirige vers la page d'accueil.
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Supprime toutes les variables de session
        $_SESSION = [];

        // Détruit la session côté serveur
        session_destroy();

        header("Location: index.php");
        exit;
    }
}
?>
