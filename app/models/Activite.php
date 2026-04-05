<?php
/**
 * Classe représentant une activité
 * Mappée sur la table 'Activite' de la base de données
 */

class Activite{
    /**
     * @var int code de l'activité (clé primaire)
     */
    private int $id;

    /**
     * @var string nom de l'activité 
     */
    private String $nomAct;

    /**
     * @var string description de l'activité 
     */
    private String $descAct;

    /**
     * @var string date de l'activité 
     */
    private String $dateAct;

    /**
     * @var float prix de l'activité 
     */
    private float $prixAct;

    /**
     * @var array tableau de congressiste
     */

        private array $CongressisteTab;


    public function __construct(int $id, string $nomAct, string $descAct, string $dateAct, float $prixAct){
        $this->id = $id;
        $this->nomAct = $nomAct;
        $this->descAct = $descAct;
        $this->dateAct = $dateAct;
        $this->prixAct = $prixAct;
        //$this->CongressisteTab = $CongressisteTab;
    }

    /**
     * getter pour le code de l'activité
     */

    public function getId():int{
        return $this->id;
    }
    
    /**
     * setter pour le code de l'activité
     */
    public function setId(int $id):void{
        $this->id = $id;
    }

    /**
     * getter pour le nom de l'activité
     */

    public function getNom():string{
        return $this->nomAct;
    }
    
    /**
     * setter pour le nom de l'activité
     */
    public function setNom(string $nomAct):void{
        $this->nomAct = $nomAct;
    }

    /**
     * getter pour la description de l'activité
     */

    public function getDesc():string{
        return $this->descAct;
    }
    
    /**
     * setter pour la description de l'activité
     */
    public function setDesc(string $descAct):void{
        $this->descAct = $descAct;
    }

    /**
     * getter pour la date de l'activité
     */

    public function getDate():string{
        return $this->dateAct;
    }
    
    /**
     * setter pour le code de l'activité
     */
    public function setDate(string $dateAct):void{
        $this->dateAct = $dateAct;
    }

    /**
     * getter pour le prix de l'activité
     */

    public function getPrix():float{
        return $this->prixAct;
    }
    
    /**
     * setter pour le code de l'activité
     */
    public function setPrix(float $prixAct):void{
        $this->prixAct = $prixAct;
    }

    /**
     * getter pour le tableau de congressiste
     */

    public function getCongressiste():array{
        return $this->CongressisteTab;
    }

    /**
     * setter pour remplir le tableau de congressiste
     */

    public function setCongressiste(array $CongressisteTab):void{
        $this->CongressisteTab = $CongressisteTab;
    }

    /**
     * Ajouter un congressiste à la promotion
     * 
     * @param Congressiste $etudiant
     * @return void
     */
    public function ajouterCongressiste(Congressiste $congressiste): void
    {
        $this->CongressisteTab[] = $congressiste;
        // S'assurer que le congressite a cette activté comme référence
        $congressiste->setCongressiste($this);
    }

    /**
     * Supprimer un congressiste à la promotion
     * 
     * @param Congressiste $etudiant
     * @return void
     */
    public function supprimerCongressiste(Congressiste $etudiant): void
    {
        $key = array_search($etudiant, $this->etudiants, true);
        if($key !== false){
            unset($this->etudiants[$key]);
            $this->etudiants = array_values($this->etudiants); //Réindexer le tableau
        }       
    }

}
?>