<?php
/**
 * Vue activite/edit.php — Formulaire de modification d'une activité
 *
 * Affiche un formulaire pré-rempli avec les données de l'activité à modifier.
 * Accessible uniquement à l'organisateur (admin).
 *
 * Variables attendues (fournies par ActiviteController::edit()) :
 *   $activite (Activite) — L'entité à modifier, chargée depuis la base de données
 *
 * Soumission : POST vers index.php?c=activite&a=edit&id={id}
 */
?>
<h2>Modifier une activité</h2>

<!-- L'ID de l'activité est passé dans l'URL pour que le contrôleur sache quelle entité mettre à jour -->
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
