<?php
// app/scripts/hash_passwords_simple.php
// Usage CLI recommandé : php app/scripts/hash_passwords_simple.php

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

$countTotal = 0;
$countHashed = 0;
$countSkipped = 0;

foreach ($rows as $row) {
    $countTotal++;
    $id = (int)$row['IDCongressiste'];
    $mdp = (string)$row['mdp'];

    // Si déjà un hash bcrypt (commence par $2y$ ou $2b$), on skip
    if (strpos($mdp, '$2y$') === 0 || strpos($mdp, '$2b$') === 0) {
        echo "[#{$id}] Déjà haché -> ignoré." . PHP_EOL;
        $countSkipped++;
        continue;
    }

    // Hache le mot de passe clair et met à jour
    $hash = password_hash($mdp, PASSWORD_BCRYPT);
    $upd = $pdo->prepare("UPDATE congressiste_b SET mdp = :mdp WHERE IDCongressiste = :id");
    $upd->execute([':mdp' => $hash, ':id' => $id]);

    echo "[#{$id}] Mot de passe haché." . PHP_EOL;
    $countHashed++;
}

echo PHP_EOL . "Résumé : total={$countTotal}, hachés={$countHashed}, ignorés={$countSkipped}" . PHP_EOL;
echo "Terminé." . PHP_EOL;
