<?php
/**
 * Script utilitaire — Hachage des mots de passe en base de données
 *
 * Ce script est destiné à être exécuté UNE SEULE FOIS en ligne de commande
 * pour migrer les mots de passe stockés en clair vers un format sécurisé bcrypt.
 *
 * Usage recommandé (CLI uniquement, ne pas exposer via le navigateur) :
 *   php app/models/script.php
 *
 * Comportement :
 *   - Parcourt tous les congressistes de la table 'congressiste_b'
 *   - Si le mot de passe est déjà haché (commence par $2y$ ou $2b$), il est ignoré
 *   - Sinon, il est haché avec PASSWORD_BCRYPT et mis à jour en base
 *
 * Affiche un résumé final : nombre total traité, hachés, ignorés.
 */

$DB_HOST = 'localhost';
$DB_NAME = 'congresanalim';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur connexion BDD : " . $e->getMessage() . PHP_EOL);
}

echo "Début du hachage des mots de passe (" . date('Y-m-d H:i:s') . ")" . PHP_EOL;

$stmt = $pdo->query("SELECT IDCongressiste, mdp FROM congressiste_b");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$countTotal   = 0;
$countHashed  = 0;
$countSkipped = 0;

foreach ($rows as $row) {
    $countTotal++;
    $id  = (int) $row['IDCongressiste'];
    $mdp = (string) $row['mdp'];

    // Détection d'un hash bcrypt existant — on ne re-hache pas
    if (strpos($mdp, '$2y$') === 0 || strpos($mdp, '$2b$') === 0) {
        echo "[#{$id}] Déjà haché -> ignoré." . PHP_EOL;
        $countSkipped++;
        continue;
    }

    // Hachage du mot de passe en clair et mise à jour en base
    $hash = password_hash($mdp, PASSWORD_BCRYPT);
    $upd  = $pdo->prepare("UPDATE congressiste_b SET mdp = :mdp WHERE IDCongressiste = :id");
    $upd->execute([':mdp' => $hash, ':id' => $id]);

    echo "[#{$id}] Mot de passe haché." . PHP_EOL;
    $countHashed++;
}

echo PHP_EOL . "Résumé : total={$countTotal}, hachés={$countHashed}, ignorés={$countSkipped}" . PHP_EOL;
echo "Terminé." . PHP_EOL;
