<?php
/**
 * Point d'entrée de l'application — Routeur principal
 *
 * Ce fichier joue le rôle de routeur frontal (front controller).
 * Il lit les paramètres GET "c" (contrôleur) et "a" (action),
 * instancie le contrôleur correspondant et appelle la méthode demandée.
 *
 * Exemples d'URLs :
 *   index.php                        → HomeController::index()
 *   index.php?c=auth&a=login         → AuthController::login()
 *   index.php?c=activite&a=read      → ActiviteController::read()
 *   index.php?c=activite&a=edit&id=3 → ActiviteController::edit()
 */

session_start();

// Récupération du contrôleur et de l'action depuis l'URL (valeurs par défaut : home / index)
$controller = $_GET["c"] ?? "home";
$action     = $_GET["a"] ?? "index";

// Construction du nom de classe et du chemin vers le fichier contrôleur
$controllerName = ucfirst($controller) . "Controller";
$controllerFile = __DIR__ . "/app/controllers/" . $controllerName . ".php";

if (file_exists($controllerFile)) {
    require $controllerFile;
    $ctrl = new $controllerName();

    // Vérification que la méthode (action) existe dans le contrôleur
    if (method_exists($ctrl, $action)) {
        $ctrl->$action();
    } else {
        echo "Action introuvable";
    }
} else {
    echo "Contrôleur introuvable";
}
?>
