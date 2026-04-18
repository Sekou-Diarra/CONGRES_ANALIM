<?php
/**
 * Vue activite/list.php — Liste de toutes les activités
 *
 * Affiche un tableau de toutes les activités du congrès.
 * Le contenu affiché varie selon le rôle de l'utilisateur connecté :
 *
 *   - Non connecté          → lien "Connexion requise" à la place des boutons
 *   - Organisateur (id=1)   → boutons Modifier et Supprimer, pas d'inscription
 *   - Participant connecté  → boutons S'inscrire et Annuler l'inscription
 *
 * Variables attendues (fournies par ActiviteController::read()) :
 *   $activites (Activite[]) — Tableau d'objets Activite à afficher
 */
?>
<h2>Liste des activités</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de l'activité</th>
            <th>Description</th>
            <th>Date</th>
            <th>Prix</th>
            <?php if (isset($_SESSION['congressiste']) && $_SESSION['congressiste']['id'] === 1): ?>
                <!-- Colonne Actions visible uniquement pour l'organisateur -->
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($activites as $uneactivite): ?>
            <tr>
                <td><?= (int) $uneactivite->getId() ?></td>
                <td><?= htmlspecialchars($uneactivite->getNom()) ?></td>
                <td><?= htmlspecialchars($uneactivite->getDesc()) ?></td>
                <td><?= htmlspecialchars($uneactivite->getDate()) ?></td>
                <!-- Formatage du prix avec 2 décimales, séparateur virgule -->
                <td><?= number_format((float) $uneactivite->getPrix(), 2, ',', ' ') ?> €</td>

                <?php if (isset($_SESSION['congressiste']) && $_SESSION['congressiste']['id'] === 1): ?>
                    <!-- Boutons Modifier / Supprimer pour l'organisateur uniquement -->
                    <td>
                        <a href="index.php?c=activite&a=edit&id=<?= $uneactivite->getId() ?>" class="btn-edit">Modifier</a> |
                        <a href="index.php?c=activite&a=delete&id=<?= $uneactivite->getId() ?>" class="btn-delete">Supprimer</a>
                    </td>
                <?php endif; ?>
            </tr>

            <!-- Ligne d'actions d'inscription sous chaque activité -->
            <tr class="actions-row">
                <td colspan="<?= (isset($_SESSION['congressiste']) && $_SESSION['congressiste']['id'] === 1) ? 6 : 5 ?>">
                    <?php if (!isset($_SESSION['congressiste'])): ?>
                        <!-- Visiteur non connecté : invitation à se connecter -->
                        <a href="index.php?c=auth&a=login">Connexion requise pour vous inscrire</a>

                    <?php elseif ($_SESSION['congressiste']['id'] === 1): ?>
                        <!-- Organisateur : message informatif, pas d'inscription possible -->
                        <span class="admin-hint">Compte organisateur : les inscriptions aux activités sont réservées aux participants.</span>

                    <?php else: ?>
                        <!-- Participant connecté : boutons S'inscrire et Annuler -->
                        <form class="inline-form" action="index.php?c=inscription&a=inscrire" method="POST">
                            <input type="hidden" name="IDActivite" value="<?= $uneactivite->getId() ?>">
                            <button type="submit" name="inscrire">S'inscrire</button>
                        </form>
                        <form class="inline-form" action="index.php?c=inscription&a=cancel" method="POST">
                            <input type="hidden" name="IDActivite" value="<?= $uneactivite->getId() ?>">
                            <button type="submit" name="cancel">Annuler l'inscription</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
