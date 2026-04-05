<h2>Liste des activités</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de l'activité</th>
            <th>Description</th>
            <th>Date</th>
            <th>Prix</th>
            <?php if(isset($_SESSION['congressiste'])): ?>
            <?php if($_SESSION['congressiste']['id'] === 1): ?>
            <th>Actions</th>
            <?php endif; ?>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($activites as $uneactivite): ?>
            <tr>
                <td><?= (int)$uneactivite->getId(); ?></td>
                <td><?= htmlspecialchars($uneactivite->getNom()); ?></td>
                <td><?= htmlspecialchars($uneactivite->getDesc()); ?></td>
                <td><?= htmlspecialchars($uneactivite->getDate()); ?></td>
                <td><?= number_format((float)$uneactivite->getPrix(), 2, ',', ' '); ?> €</td>
            <?php if(isset($_SESSION['congressiste'])): ?>
            <?php if($_SESSION['congressiste']['id'] === 1): ?>
                <td>
                    <a href="index.php?c=activite&a=edit&id=<?= $uneactivite->getId(); ?>" class="btn-edit">Modifier</a> |
                    <a class="btn-delete" href="index.php?c=activite&a=delete&id=<?= $uneactivite->getId(); ?>">Supprimer</a>
                </td>
            <?php endif; ?>
            <?php endif; ?>
            </tr>
            <tr class="actions-row">
                <td colspan="<?= (isset($_SESSION['congressiste']) && $_SESSION['congressiste']['id'] === 1) ? 6 : 5 ?>">
                    <?php if(!isset($_SESSION['congressiste'])): ?>
                        <a href="index.php?c=auth&a=login">Connexion requise pour vous inscrire</a>
                    <?php elseif($_SESSION['congressiste']['id'] === 1): ?>
                        <span class="admin-hint">Compte organisateur : les inscriptions aux activités sont réservées aux participants.</span>
                    <?php else: ?>
                        <form class="inline-form" action="index.php?c=inscription&a=inscrire" method="POST">
                            <input type="hidden" name="IDActivite" value="<?= $uneactivite->getId(); ?>">
                            <button type="submit" name="inscrire">S'inscrire</button>
                        </form>
                        <form class="inline-form" action="index.php?c=inscription&a=cancel" method="POST">
                            <input type="hidden" name="IDActivite" value="<?= $uneactivite->getId(); ?>">
                            <button type="submit" name="cancel">Annuler l'inscription</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
