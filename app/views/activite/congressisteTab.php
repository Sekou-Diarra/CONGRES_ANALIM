<h2>Voir les inscrits par activité</h2>
<form method="POST">
    <label>Choisir une activité :</label>
    <select name="activite" onchange="this.form.submit()">
        <option value="">-- Toutes les activités --</option>
        <?php foreach ($activites as $act): ?>
            <option value="<?= $act->getId() ?>" <?= (isset($_POST['activite']) && $_POST['activite'] == $act->getId()) ? 'selected' : '' ?>>
                <?= htmlspecialchars($act->getNom()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php if (!empty($Congressistes)): ?>
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