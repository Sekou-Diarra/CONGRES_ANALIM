<?php
/**
 * Vue activite/congressisteTab.php — Inscrits par activité (vue admin)
 *
 * Accessible uniquement à l'organisateur (IDCongressiste = 1).
 * Affiche une liste déroulante permettant de sélectionner une activité,
 * puis liste les congressistes qui y sont inscrits.
 * La sélection d'une activité soumet automatiquement le formulaire (onchange).
 *
 * Variables attendues (fournies par ActiviteController::getCongressistesByActivite()) :
 *   $activites     (Activite[])     — Liste de toutes les activités pour la liste déroulante
 *   $Congressistes (Congressiste[]) — Liste des inscrits à l'activité sélectionnée (vide si aucune)
 */
?>
<h2>Voir les inscrits par activité</h2>

<!-- Formulaire de sélection d'activité — soumis automatiquement au changement de sélection -->
<form method="POST">
    <label>Choisir une activité :</label>
    <select name="activite" onchange="this.form.submit()">
        <option value="">-- Toutes les activités --</option>
        <?php foreach ($activites as $act): ?>
            <option value="<?= $act->getId() ?>"
                <?= (isset($_POST['activite']) && $_POST['activite'] == $act->getId()) ? 'selected' : '' ?>>
                <?= htmlspecialchars($act->getNom()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if (!empty($Congressistes)): ?>
    <!-- Tableau des congressistes inscrits à l'activité sélectionnée -->
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Mail</th>
        </tr>
        <?php foreach ($Congressistes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c->getNomCong()) ?></td>
                <td><?= htmlspecialchars($c->getPrenomCong()) ?></td>
                <td><?= htmlspecialchars($c->getMail()) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucun inscrit pour l'activité sélectionnée.</p>
<?php endif; ?>
