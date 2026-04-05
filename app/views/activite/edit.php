<h2>Modifier une activité</h2>

<form action="index.php?c=activite&a=edit&id=<?= $activite->getId() ?>" method="POST">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($activite->getNom()) ?>" required>

    <label for="description">Description :</label>
    <input type="text" id="description" name="description" value="<?= htmlspecialchars($activite->getDesc()) ?>" required>

    <label for="date">Date :</label>
    <input type="date" id="date" name="date" value="<?= htmlspecialchars($activite->getDate()) ?>" required>

    <label for="prix">Prix :</label>
    <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?= htmlspecialchars($activite->getPrix()) ?>" required>

    <button type="submit">Enregistrer les modifications</button>
</form>

<a href="index.php?c=activite&a=read">← Retour à la liste</a>
