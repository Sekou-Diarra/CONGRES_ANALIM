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