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
    <?php if(!isset($_SESSION['congressiste'])):?>
    <a href="index.php?c=auth&a=register">Inscription</a>
    <a href="index.php?c=auth&a=login">Connexion</a>
    <?php else:?>
    <span>Bienvenue, <?= htmlspecialchars($_SESSION['congressiste']['prenom']) ?> 👋</span>
    <?php if($_SESSION['congressiste']['id'] === 1): ?>
    <a href="index.php?c=activite&a=add">Ajouter une activité</a>
    <a href="index.php?c=activite&a=getCongressistesByActivite">Voir les inscrits par activité</a>
    <?php else: ?>
    <a href="index.php?c=inscription&a=getActivitesByCongressiste">Voir mes inscriptions</a>
    <?php endif;?>
    <a href="index.php?c=auth&a=logout">Déconnexion</a>
    <?php endif;?>
</nav>
<p>Congrès Analim</p>
<main>
        <?php if (isset($_SESSION['flash'])): ?>
            <p class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type']) ?>">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </p>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <?php
        // ✅ C’est ici que la vue spécifique (ex: list.php) s’affiche
        if (isset($content)) {
            include $content;
        }
        ?>
    </main>
    <!--
<div>
    <?php
    /*
      $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
      $method = $trace[1]["function"];
      $controller = strtolower(str_replace("Controller","",get_class($this)));
      $viewFile = __DIR__ . "/" . $controller . "/" . $method . ".php";
      if (file_exists($viewFile)) include $viewFile;
      */
    ?>
</div>
-->

</body>
</html>