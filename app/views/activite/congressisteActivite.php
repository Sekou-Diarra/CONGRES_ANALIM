<h2>Mes activités</h2>
<table>
    <thead>
        <tr>
            <th>Nom de l'activité</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($Activites as $uneActivite):?>
            <tr>
                <td><?= htmlspecialchars($uneActivite->getNom()); ?></td>
                <td><?= htmlspecialchars($uneActivite->getDesc()); ?></td>
                <td><?= htmlspecialchars($uneActivite->getDate()); ?></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>