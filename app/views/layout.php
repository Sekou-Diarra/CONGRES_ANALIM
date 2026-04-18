<?php
/**
 * Vue layout.php — Template principal partagé
 *
 * Ce fichier constitue le squelette HTML commun à toutes les pages.
 * Il est inclus par chaque contrôleur et affiche :
 *   - La barre de navigation (liens adaptés selon le rôle de l'utilisateur)
 *   - Les messages flash de session (succès ou erreur)
 *   - Le contenu de la vue spécifique via la variable $content
 *
 * Navigation conditionnelle :
 *   - Non connecté       → liens Inscription et Connexion
 *   - Connecté (admin)   → liens Ajouter activité et Voir les inscrits
 *   - Connecté (participant) → lien Mes inscriptions
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Congrès</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,500&family=Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/activite.css">
</head>
<body>

<nav>
    <a href="index.php?c=home&a=index">Accueil</a>
    <a href="index.php?c=activite&a=read">Voir la liste de toutes les activités</a>

    <?php if (!isset($_SESSION['congressiste'])): ?>
        <!-- Liens visibles uniquement pour les visiteurs non connectés -->
        <a href="index.php?c=auth&a=register">Inscription</a>
        <a href="index.php?c=auth&a=login">Connexion</a>

    <?php else: ?>
        <!-- Message de bienvenue affiché pour tout utilisateur connecté -->
        <span>Bienvenue, <?= htmlspecialchars($_SESSION['congressiste']['prenom']) ?> 👋</span>

        <?php if ($_SESSION['congressiste']['id'] === 1): ?>
            <!-- Liens réservés à l'organisateur (IDCongressiste = 1) -->
            <a href="index.php?c=activite&a=add">Ajouter une activité</a>
            <a href="index.php?c=activite&a=getCongressistesByActivite">Voir les inscrits par activité</a>

        <?php else: ?>
            <!-- Lien réservé aux participants -->
            <a href="index.php?c=inscription&a=getActivitesByCongressiste">Voir mes inscriptions</a>

        <?php endif; ?>

        <a href="index.php?c=auth&a=logout">Déconnexion</a>
    <?php endif; ?>
</nav>

<p>Congrès Analim</p>

<main>
    <?php if (isset($_SESSION['flash'])): ?>
        <!-- Affichage du message flash stocké en session puis suppression immédiate -->
        <p class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </p>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php
    // Inclusion de la vue spécifique définie par le contrôleur via $content
    if (isset($content)) {
        include $content;
    }
    ?>
</main>

</body>
</html>
