<?php
if(!empty($error)){
    echo "<p class='alert alert-error'>" . htmlspecialchars($error) . "</p>";
}
?>

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
