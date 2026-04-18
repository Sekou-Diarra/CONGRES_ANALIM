<?php
/**
 * Vue activite/add.php — Formulaire d'ajout d'une activité
 *
 * Affiche le formulaire permettant à l'organisateur (admin) d'ajouter
 * une nouvelle activité au programme du congrès.
 * Accessible uniquement à l'administrateur (IDCongressiste = 1).
 *
 * Soumission : POST vers index.php?c=activite&a=add
 * Champs requis : nom, description, date, prix (>= 0)
 */
?>
<h2>Ajouter une activité</h2>
<form action="index.php?c=activite&a=add" method="POST">
    <label for="nom">Nom de l'activité :</label>
    <input type="text" name="nom" id="nom" required>

    <label for="description">Description de l'activité :</label>
    <input type="text" name="description" id="description" required>

    <label for="date">Date de l'activité :</label>
    <input type="date" name="date" id="date" required>

    <label for="prix">Prix de l'activité :</label>
    <input type="number" step="0.01" min="0" name="prix" id="prix" required>

    <button type="submit" name="AJOUTER">Ajouter</button>
</form>
