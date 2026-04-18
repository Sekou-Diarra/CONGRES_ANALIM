<?php
/**
 * Entité Congressiste
 *
 * Représente un participant (ou l'organisateur) du congrès.
 * Mappée sur la table 'congressiste_b' de la base de données.
 *
 * Colonnes correspondantes :
 *   IDCongressiste       → $id
 *   nomCongressiste      → $nomCong
 *   prenomCongressiste   → $prenomCong
 *   adresseCongressiste  → $adrCong
 *   dateInscription      → $dateICong
 *   mailCongressiste     → $mailCong
 *   mdp                  → $password
 */
class Congressiste {

    /** @var int Identifiant unique du congressiste (clé primaire, AUTO_INCREMENT) */
    private int $id;

    /** @var string Nom du congressiste */
    private string $nomCong;

    /** @var string Prénom du congressiste */
    private string $prenomCong;

    /** @var string Adresse postale du congressiste */
    private string $adrCong;

    /** @var string Date d'inscription au congrès (format Y-m-d) */
    private string $dateICong;

    /** @var string Adresse email du congressiste (utilisée comme identifiant de connexion) */
    private string $mailCong;

    /** @var string Mot de passe hashé (bcrypt via password_hash) */
    private string $password;

    /**
     * Constructeur de l'entité Congressiste.
     *
     * @param int    $id          Identifiant
     * @param string $nomCong     Nom
     * @param string $prenomCong  Prénom
     * @param string $adrCong     Adresse postale
     * @param string $dateICong   Date d'inscription
     * @param string $mailCong    Email
     * @param string $password    Mot de passe hashé
     */
    public function __construct(int $id, string $nomCong, string $prenomCong, string $adrCong, string $dateICong, string $mailCong, string $password) {
        $this->id         = $id;
        $this->nomCong    = $nomCong;
        $this->prenomCong = $prenomCong;
        $this->adrCong    = $adrCong;
        $this->dateICong  = $dateICong;
        $this->mailCong   = $mailCong;
        $this->password   = $password;
    }

    /** @return int Identifiant du congressiste */
    public function getIdCong(): int { return $this->id; }

    /** @param int $id Nouvel identifiant */
    public function setId(int $id): void { $this->id = $id; }

    /** @return string Nom du congressiste */
    public function getNomCong(): string { return $this->nomCong; }

    /** @param string $nomCong Nouveau nom */
    public function setNomCong(string $nomCong): void { $this->nomCong = $nomCong; }

    /** @return string Prénom du congressiste */
    public function getPrenomCong(): string { return $this->prenomCong; }

    /** @param string $prenomCong Nouveau prénom */
    public function setPrenomCong(string $prenomCong): void { $this->prenomCong = $prenomCong; }

    /** @return string Adresse postale */
    public function getAdr(): string { return $this->adrCong; }

    /** @param string $adrCong Nouvelle adresse */
    public function setAdr(string $adrCong): void { $this->adrCong = $adrCong; }

    /** @return string Date d'inscription */
    public function getDateI(): string { return $this->dateICong; }

    /** @param string $dateICong Nouvelle date d'inscription */
    public function setDateI(string $dateICong): void { $this->dateICong = $dateICong; }

    /** @return string Email du congressiste */
    public function getMail(): string { return $this->mailCong; }

    /** @param string $mailCong Nouvel email */
    public function setMail(string $mailCong): void { $this->mailCong = $mailCong; }

    /** @return string Mot de passe hashé */
    public function getPassword(): string { return $this->password; }
}
?>
