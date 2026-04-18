<?php
/**
 * Contrôleur ActiviteController
 *
 * Gère toutes les opérations CRUD sur les activités du congrès,
 * ainsi que la consultation des congressistes inscrits à une activité.
 *
 * Actions disponibles :
 *   - add()                       : affiche le formulaire et crée une activité
 *   - read()                      : liste toutes les activités (ou une seule par ID)
 *   - edit()                      : affiche le formulaire et met à jour une activité
 *   - delete()                    : supprime une activité et ses inscriptions associées
 *   - getCongressistesByActivite() : liste les congressistes inscrits à une activité
 */

require_once __DIR__ . "/../models/Activite.php";
require_once __DIR__ . "/../models/ActiviteRepository.php";
require_once __DIR__ . "/../core/Database.php";

class ActiviteController {

    /**
     * Stocke un message flash en session pour l'afficher à la prochaine page.
     *
     * @param string $type    Type du message : 'success' ou 'error'
     * @param string $message Contenu du message à afficher
     */
    private function flash(string $type, string $message): void {
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message
        ];
    }

    /**
     * Affiche le formulaire d'ajout d'une activité et traite sa soumission.
     *
     * - Vérifie que tous les champs sont remplis et valides.
     * - Crée un objet Activite et le persiste via ActiviteRepository.
     * - Redirige vers la liste en cas de succès.
     */
    public function add() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new ActiviteRepository($pdo);

        if (isset($_POST['nom']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['prix'])) {
            $nom         = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $date        = $_POST['date'];
            $prix        = (float) $_POST['prix'];

            // Validation : aucun champ vide, prix positif ou nul
            if ($nom === '' || $description === '' || $date === '' || $prix < 0) {
                $this->flash('error', "Merci de remplir correctement tous les champs.");
                header("Location: index.php?c=activite&a=add");
                exit;
            }

            // Création de l'entité (id=0 car l'AUTO_INCREMENT le définit en base)
            $activite = new Activite(0, $nom, $description, $date, $prix);
            $result   = $repo->create($activite);

            if ($result) {
                $this->flash('success', "Activité ajoutée avec succès.");
                header("Location: index.php?c=activite&a=read");
                exit;
            } else {
                $this->flash('error', "L'ajout de l'activité a échoué.");
                header("Location: index.php?c=activite&a=add");
                exit;
            }
        }

        $content = __DIR__ . '/../views/activite/add.php';
        include_once __DIR__ . '/../views/layout.php';
    }

    /**
     * Affiche la liste de toutes les activités.
     *
     * - Si un ID est fourni en POST, affiche uniquement l'activité correspondante.
     * - Sinon, affiche toutes les activités via findAll().
     */
    public function read() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new ActiviteRepository($pdo);

        if (isset($_POST['id'])) {
            // Recherche d'une activité spécifique par ID
            $id       = $_POST['id'];
            $activite = $repo->findById($id);
            $activites = $activite ? [$activite] : [];
        } else {
            // Récupération de toutes les activités
            $activites = $repo->findAll();
        }

        $content = __DIR__ . '/../views/activite/list.php';
        require __DIR__ . '/../views/layout.php';
    }

    /**
     * Affiche le formulaire de modification d'une activité et traite sa soumission.
     *
     * - Requiert un paramètre GET 'id' valide.
     * - Charge l'activité existante, met à jour ses propriétés et persiste les changements.
     * - Redirige vers la liste en cas de succès ou d'erreur.
     */
    public function edit() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new ActiviteRepository($pdo);

        // Vérification de la présence de l'ID dans l'URL
        if (!isset($_GET['id'])) {
            $this->flash('error', "Aucun ID fourni.");
            header("Location: index.php?c=activite&a=read");
            return;
        }

        $id       = (int) $_GET['id'];
        $activite = $repo->findById($id);

        if (!$activite) {
            $this->flash('error', "Activité introuvable.");
            header("Location: index.php?c=activite&a=read");
            return;
        }

        if (isset($_POST['nom']) && isset($_POST['description']) && isset($_POST['date']) && isset($_POST['prix'])) {
            $nom         = trim($_POST['nom']);
            $description = trim($_POST['description']);
            $date        = $_POST['date'];
            $prix        = (float) $_POST['prix'];

            // Validation des champs du formulaire
            if ($nom === '' || $description === '' || $date === '' || $prix < 0) {
                $this->flash('error', "Merci de remplir correctement tous les champs.");
                header("Location: index.php?c=activite&a=edit&id=" . $id);
                exit;
            }

            // Mise à jour des propriétés de l'entité
            $activite->setNom($nom);
            $activite->setDesc($description);
            $activite->setDate($date);
            $activite->setPrix($prix);

            if ($repo->update($activite)) {
                $this->flash('success', "Activité modifiée.");
                header("Location: index.php?c=activite&a=read");
                exit;
            } else {
                $this->flash('error', "La modification a échoué.");
                header("Location: index.php?c=activite&a=edit&id=" . $id);
                exit;
            }
        }

        $content = __DIR__ . '/../views/activite/edit.php';
        include __DIR__ . '/../views/layout.php';
    }

    /**
     * Supprime une activité et toutes ses inscriptions associées.
     *
     * - Requiert un paramètre GET 'id'.
     * - La suppression est effectuée dans une transaction :
     *   d'abord les lignes dans 'participer', puis l'activité elle-même.
     * - Redirige vers la liste dans tous les cas.
     */
    public function delete() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new ActiviteRepository($pdo);

        if (!isset($_GET['id'])) {
            $this->flash('error', "Aucun ID fourni.");
            header("Location: index.php?c=activite&a=read");
            return;
        }

        $id        = (int) $_GET['id'];
        $supprimer = $repo->delete($id);

        if ($supprimer) {
            $this->flash('success', "Activité supprimée.");
        } else {
            $this->flash('error', "La suppression n'a pas fonctionné.");
        }

        header("Location: index.php?c=activite&a=read");
        exit;
    }

    /**
     * Affiche la liste des congressistes inscrits à une activité donnée.
     *
     * - Charge toutes les activités pour alimenter la liste déroulante.
     * - Si une activité est sélectionnée (POST 'activite'), charge les inscrits.
     * - Sinon, retourne un tableau vide (aucune activité sélectionnée).
     */
    public function getCongressistesByActivite() {
        $db        = new Database();
        $pdo       = $db->getConnexion();
        $repo      = new ActiviteRepository($pdo);
        $activites = $repo->findAll();

        if (isset($_POST['activite']) && !empty($_POST['activite'])) {
            // Récupération des congressistes inscrits à l'activité sélectionnée
            $id             = (int) $_POST['activite'];
            $Congressistes  = $repo->getCongressistesByActivite($id);
        } else {
            // Aucune activité sélectionnée : liste vide
            $Congressistes = [];
        }

        $content = __DIR__ . '/../views/activite/congressisteTab.php';
        include_once __DIR__ . '/../views/layout.php';
    }
}
?>
