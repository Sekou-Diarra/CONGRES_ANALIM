<?php
/**
 * Contrôleur HomeController
 *
 * Gère la page d'accueil de l'application.
 * C'est le contrôleur par défaut chargé quand aucun paramètre
 * "c" n'est fourni dans l'URL (index.php sans paramètres).
 */
class HomeController {

    /**
     * Affiche la page d'accueil.
     * Inclut le layout principal qui charge la vue home/index.php.
     */
    public function index() {
        include __DIR__ . "/../views/layout.php";
    }
}
?>
