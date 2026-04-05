<?php
if(!empty($error)){
    echo "<p class='alert alert-error'>" . htmlspecialchars($error) . "</p>";
}
if(!empty($success)){
    echo "<p class='alert alert-success'>" . htmlspecialchars($success) . "</p>";
}
?>

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
