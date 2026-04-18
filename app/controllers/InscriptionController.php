<?php
/**
 * Contrôleur InscriptionController
 *
 * Gère les inscriptions et désinscriptions des congressistes aux activités.
 * Applique une restriction : le compte administrateur (IDCongressiste = 1)
 * ne peut pas s'inscrire aux activités en tant que participant.
 *
 * Actions disponibles :
 *   - inscrire()                  : inscrit le congressiste connecté à une activité
 *   - cancel()                    : annule l'inscription du congressiste à une activité
 *   - getActivitesByCongressiste() : affiche les activités auxquelles le congressiste est inscrit
 */

require_once __DIR__ . "/../models/Congressiste.php";
require_once __DIR__ . "/../models/CongressisteRepository.php";
require_once __DIR__ . "/../core/Database.php";

class InscriptionController {

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
     * Vérifie si le congressiste connecté est l'administrateur (organisateur).
     * L'administrateur correspond à l'IDCongressiste = 1 en base de données.
     *
     * @return bool true si l'utilisateur est admin, false sinon
     */
    private function isAdmin(): bool {
        return isset($_SESSION['congressiste']['id'])
            && (int) $_SESSION['congressiste']['id'] === 1;
    }

    /**
     * Inscrit le congressiste connecté à une activité.
     *
     * - Bloque l'accès si l'utilisateur est administrateur.
     * - Vérifie que l'utilisateur est connecté et qu'un IDActivite est fourni en POST.
     * - Détecte une double inscription et retourne un message approprié.
     * - Redirige vers la liste des activités dans tous les cas.
     */
    public function inscrire() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new CongressisteRepository($pdo);

        // L'administrateur ne peut pas s'inscrire comme participant
        if ($this->isAdmin()) {
            $this->flash('error', "L'organisateur ne peut pas s'inscrire aux activités en tant que participant.");
            header("Location: index.php?c=activite&a=read");
            exit;
        }

        if (isset($_SESSION['congressiste']) && isset($_POST['IDActivite'])) {
            $inscription = $repo->inscrire([
                "IDCongressiste" => (int) $_SESSION['congressiste']['id'],
                "IDActivite"     => (int) $_POST['IDActivite']
            ]);

            if ($inscription === "exists") {
                $this->flash('error', "Vous êtes déjà inscrit à cette activité.");
            } elseif ($inscription) {
                $this->flash('success', "Inscription réussie.");
            } else {
                $this->flash('error', "Inscription échouée.");
            }
        } else {
            $this->flash('error', "Vous devez vous connecter pour vous inscrire.");
        }

        header("Location: index.php?c=activite&a=read");
        exit;
    }

    /**
     * Annule l'inscription du congressiste connecté à une activité.
     *
     * - Bloque l'accès si l'utilisateur est administrateur.
     * - Vérifie que l'utilisateur est connecté et qu'un IDActivite est fourni en POST.
     * - Redirige vers la liste des activités dans tous les cas.
     */
    public function cancel() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new CongressisteRepository($pdo);

        // L'administrateur n'a pas d'inscriptions personnelles à annuler
        if ($this->isAdmin()) {
            $this->flash('error', "L'organisateur ne gère pas d'inscriptions personnelles aux activités.");
            header("Location: index.php?c=activite&a=read");
            exit;
        }

        if (isset($_SESSION['congressiste']) && isset($_POST['IDActivite'])) {
            $inscription = $repo->cancel([
                "IDCongressiste" => (int) $_SESSION['congressiste']['id'],
                "IDActivite"     => (int) $_POST['IDActivite']
            ]);

            if ($inscription) {
                $this->flash('success', "Inscription annulée.");
            } else {
                $this->flash('error', "L'inscription n'a pas pu être annulée.");
            }
        } else {
            $this->flash('error', "Vous devez vous connecter pour annuler.");
        }

        header("Location: index.php?c=activite&a=read");
        exit;
    }

    /**
     * Affiche la liste des activités auxquelles le congressiste connecté est inscrit.
     *
     * - Réservée aux participants (bloque l'admin).
     * - Requiert une session active avec un IDCongressiste.
     * - Redirige vers la connexion si l'utilisateur n'est pas connecté.
     */
    public function getActivitesByCongressiste() {
        $db   = new Database();
        $pdo  = $db->getConnexion();
        $repo = new CongressisteRepository($pdo);

        // Page réservée aux participants, pas à l'organisateur
        if ($this->isAdmin()) {
            $this->flash('error', "La page « mes inscriptions » est réservée aux participants.");
            header("Location: index.php?c=activite&a=read");
            exit;
        }

        if (!isset($_SESSION['congressiste']['id'])) {
            $this->flash('error', "Vous devez vous connecter d'abord.");
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $id       = (int) $_SESSION['congressiste']['id'];
        $Activites = $repo->getActivitesByCongressiste($id);

        $content = __DIR__ . '/../views/activite/congressisteActivite.php';
        include_once __DIR__ . '/../views/layout.php';
    }
}
?>
