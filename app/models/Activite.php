<?php
/**
 * Entité Activite
 *
 * Représente une activité proposée lors du congrès.
 * Mappée sur la table 'activite' de la base de données.
 *
 * Colonnes correspondantes :
 *   IDActivite          → $id
 *   nomActivite         → $nomAct
 *   descriptionActivite → $descAct
 *   dateActivite        → $dateAct
 *   prixActivite        → $prixAct
 */
class Activite {

    /** @var int Identifiant unique de l'activité (clé primaire, AUTO_INCREMENT) */
    private int $id;

    /** @var string Nom de l'activité */
    private string $nomAct;

    /** @var string Description de l'activité */
    private string $descAct;

    /** @var string Date de l'activité (format Y-m-d) */
    private string $dateAct;

    /** @var float Prix de participation à l'activité (en euros) */
    private float $prixAct;

    /** @var array Liste des congressistes inscrits à cette activité */
    private array $CongressisteTab;

    /**
     * Constructeur de l'entité Activite.
     *
     * @param int    $id       Identifiant (0 pour une nouvelle entité non encore persistée)
     * @param string $nomAct   Nom de l'activité
     * @param string $descAct  Description de l'activité
     * @param string $dateAct  Date de l'activité
     * @param float  $prixAct  Prix de l'activité
     */
    public function __construct(int $id, string $nomAct, string $descAct, string $dateAct, float $prixAct) {
        $this->id      = $id;
        $this->nomAct  = $nomAct;
        $this->descAct = $descAct;
        $this->dateAct = $dateAct;
        $this->prixAct = $prixAct;
    }

    /** @return int Identifiant de l'activité */
    public function getId(): int { return $this->id; }

    /** @param int $id Nouvel identifiant */
    public function setId(int $id): void { $this->id = $id; }

    /** @return string Nom de l'activité */
    public function getNom(): string { return $this->nomAct; }

    /** @param string $nomAct Nouveau nom */
    public function setNom(string $nomAct): void { $this->nomAct = $nomAct; }

    /** @return string Description de l'activité */
    public function getDesc(): string { return $this->descAct; }

    /** @param string $descAct Nouvelle description */
    public function setDesc(string $descAct): void { $this->descAct = $descAct; }

    /** @return string Date de l'activité */
    public function getDate(): string { return $this->dateAct; }

    /** @param string $dateAct Nouvelle date */
    public function setDate(string $dateAct): void { $this->dateAct = $dateAct; }

    /** @return float Prix de l'activité */
    public function getPrix(): float { return $this->prixAct; }

    /** @param float $prixAct Nouveau prix */
    public function setPrix(float $prixAct): void { $this->prixAct = $prixAct; }

    /** @return array Tableau des congressistes inscrits */
    public function getCongressiste(): array { return $this->CongressisteTab; }

    /** @param array $CongressisteTab Nouveau tableau de congressistes */
    public function setCongressiste(array $CongressisteTab): void { $this->CongressisteTab = $CongressisteTab; }

    /**
     * Ajoute un congressiste à la liste des inscrits de cette activité.
     *
     * @param Congressiste $congressiste Le congressiste à ajouter
     */
    public function ajouterCongressiste(Congressiste $congressiste): void {
        $this->CongressisteTab[] = $congressiste;
        $congressiste->setCongressiste($this);
    }

    /**
     * Retire un congressiste de la liste des inscrits de cette activité.
     *
     * @param Congressiste $etudiant Le congressiste à retirer
     */
    public function supprimerCongressiste(Congressiste $etudiant): void {
        $key = array_search($etudiant, $this->etudiants, true);
        if ($key !== false) {
            unset($this->etudiants[$key]);
            $this->etudiants = array_values($this->etudiants);
        }
    }
}
?>
