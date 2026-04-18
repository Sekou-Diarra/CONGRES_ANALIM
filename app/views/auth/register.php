<?php
/**
 * Vue register.php — Formulaire d'inscription
 *
 * Affiche le formulaire de création de compte congressiste.
 * Les messages d'erreur ou de succès transmis par AuthController::register()
 * sont affichés en haut du formulaire.
 *
 * Variables attendues :
 *   $error   (string|null) — Message d'erreur (champs invalides, email déjà utilisé)
 *   $success (string|null) — Message de succès (non utilisé ici, géré via flash)
 *
 * Soumission : POST vers index.php?c=auth&a=register
 * Contraintes : mot de passe minimum 6 caractères, email valide
 */
?>

<?php if (!empty($error)): ?>
    <p class='alert alert-error'><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p class='alert alert-success'><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<h2>Créer un compte</h2>
<form action="" method="POST">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required>

    <label for="prenom">Prénom :</label>
    <input type="text" name="prenom" id="prenom" required>

    <label for="adresse">Adresse :</label>
    <input type="text" name="adresse" id="adresse" required>

    <label for="mail">Email :</label>
    <input type="email" name="mail" id="mail" required>

    <label for="mdp">Mot de passe :</label>
    <input type="password" name="mdp" id="mdp" required>

    <button type="submit">S'inscrire</button>
</form>

<p>Déjà inscrit ? <a href="index.php?c=auth&a=login">Se connecter</a></p>
