<?php
/**
 * Classe représentant une activité
 * Mappée sur la table 'Activite' de la base de données
 */

class Congressiste{
    /**
     * @var int code de l'activité (clé primaire)
     */
    private int $id;

    /**
     * @var string nom de l'activité 
     */
    private String $nomCong;

    /**
     * @var string prénom de l'activité 
     */
    private String $prenomCong;

    /**
     * @var string description de l'activité 
     */
    private String $adrCong;

    /**
     * @var string date de l'activité 
     */
    private String $dateICong;

    /**
     * @var string prix de l'activité 
     */
    private string $mailCong;

    /**
     * @var string prix de l'activité 
     */
    private string $password;

    /**
     * @var array tableau d'activités
     */

    //private array $ActiviteTab;

    public function __construct(int $id, string $nomCong, string $prenomCong, string $adrCong, string $dateICong, string $mailCong, string $password){
        $this->id = $id;
        $this->nomCong = $nomCong;
        $this->prenomCong = $prenomCong;
        $this->adrCong = $adrCong;
        $this->dateICong = $dateICong;
        $this->mailCong = $mailCong;
        $this->password = $password;
        //$this->ActiviteTab = $ActiviteTab;
    }

    /**
     * getter pour le code de l'activité
     */

    public function getIdCong():int{
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

    public function getNomCong():string{
        return $this->nomCong;
    }
    
    /**
     * setter pour le nom de l'activité
     */
    public function setNomCong(string $nomCong):void{
        $this->nomCong = $nomCong;
    }

    /**
     * getter pour le nom de l'activité
     */

    public function getPrenomCong():string{
        return $this->prenomCong;
    }
    
    /**
     * setter pour le nom de l'activité
     */
    public function setPrenomCong(string $prenomCong):void{
        $this->prenomCong = $prenomCong;
    }

    /**
     * getter pour l'adresse du congressiste
     */

    public function getAdr():string{
        return $this->adrCong;
    }
    
    /**
     * setter pour la description de l'activité
     */
    public function setAdr(string $adrCong):void{
        $this->adrCong = $adrCong;
    }

    /**
     * getter pour la date de l'activité
     */

    public function getDateI():string{
        return $this->dateICong;
    }
    
    /**
     * setter pour le code de l'activité
     */
    public function setDateI(string $dateICong):void{
        $this->dateICong = $dateICong;
    }

    /**
     * getter pour le prix de l'activité
     */

    public function getMail():string{
        return $this->mailCong;
    }
    
    /**
     * setter pour le code de l'activité
     */
    public function setMail(string $mailCong):void{
        $this->mailCong = $mailCong;
    }

    public function getPassword():string{
        return $this->password;
    }
/*
    public function getActivite():array{
        return $this->ActiviteTab;
    }

    public function setActivite(array $ActiviteTab):void{
        $this->ActiviteTab = $ActiviteTab;
    }
        */

}
?>