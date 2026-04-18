<?php
/**
 * Vue login.php — Formulaire de connexion
 *
 * Affiche le formulaire de connexion pour les congressistes.
 * Si la variable $error est définie par le contrôleur (AuthController::login()),
 * elle est affichée en haut du formulaire.
 *
 * Variables attendues :
 *   $error (string|null) — Message d'erreur à afficher en cas d'échec de connexion
 *
 * Soumission : POST vers index.php?c=auth&a=login
 */
?>

<?php if (!empty($error)): ?>
    <!-- Affichage de l'erreur de connexion (email/mot de passe invalide) -->
    <p class='alert alert-error'><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<h2>Connexion</h2>
<form action="" method="POST">
    <label for="mail">Email :</label>
    <input type="email" name="mail" id="mail" required>

    <label for="mdp">Mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required>

    <button type="submit" name="CONNEXION">Connexion</button>
</form>

<p>
    Pas encore inscrit ?
    <a href="index.php?c=auth&a=register">Inscrivez-vous</a>
</p>
