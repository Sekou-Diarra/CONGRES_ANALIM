# Congrès ANALIM

Application web de gestion d'un congrès scientifique — inscription des congressistes, gestion des activités et suivi des participations.

## Contexte

Projet scolaire réalisé dans le cadre du BTS SIO option SLAM au Lycée Suzanne Valadon (Limoges).

L'application permet de gérer les participants d'un congrès (les congressistes), les activités proposées durant l'événement, ainsi que les inscriptions de chaque participant à ces activités. Un compte organisateur (administrateur) dispose de droits étendus pour gérer le catalogue d'activités et consulter les listes de participants.

## Fonctionnalités

**Congressiste (participant)**
- Créer un compte et se connecter
- Consulter la liste des activités disponibles
- S'inscrire à une activité
- Annuler une inscription
- Consulter ses propres inscriptions

**Organisateur (administrateur)**
- Ajouter, modifier et supprimer des activités
- Consulter la liste des congressistes inscrits à une activité donnée

## Architecture

Le projet suit le pattern **MVC (Modèle-Vue-Contrôleur)** implémenté manuellement en PHP pur, sans framework.

```
CONGRES_ANALIM/
├── index.php                          # Point d'entrée — routeur principal
├── app/
│   ├── controllers/
│   │   ├── ActiviteController.php     # CRUD activités + liste des inscrits
│   │   ├── AuthController.php         # Connexion, inscription, déconnexion
│   │   ├── HomeController.php         # Page d'accueil
│   │   └── InscriptionController.php  # Inscription / annulation aux activités
│   ├── core/
│   │   └── Database.php               # Connexion PDO à la base de données
│   ├── models/
│   │   ├── Activite.php               # Entité Activite (getters/setters)
│   │   ├── ActiviteRepository.php     # Requêtes SQL liées aux activités
│   │   ├── Congressiste.php           # Entité Congressiste (getters/setters)
│   │   └── CongressisteRepository.php # Requêtes SQL liées aux congressistes
│   └── views/
│       ├── layout.php                 # Template principal partagé
│       ├── auth/
│       │   ├── login.php              # Formulaire de connexion
│       │   └── register.php           # Formulaire d'inscription
│       ├── activite/
│       │   ├── list.php               # Liste des activités
│       │   ├── add.php                # Formulaire d'ajout
│       │   ├── edit.php               # Formulaire de modification
│       │   ├── congressisteActivite.php # Mes inscriptions (vue congressiste)
│       │   └── congressisteTab.php    # Inscrits par activité (vue admin)
│       └── home/
│           └── index.php              # Page d'accueil
├── public/
│   └── activite.css                   # Feuille de styles
└── sql/
    ├── jeu_donnees.sql                # Données de test
    └── vider_donnees.sql              # Script de remise à zéro
```

## Routage

Le routage est géré par `index.php` via les paramètres GET `c` (contrôleur) et `a` (action) :

```
index.php?c=activite&a=read        → liste des activités
index.php?c=activite&a=add         → ajouter une activité
index.php?c=activite&a=edit&id=1   → modifier l'activité n°1
index.php?c=auth&a=login           → connexion
index.php?c=auth&a=register        → inscription
index.php?c=inscription&a=inscrire → s'inscrire à une activité
```

## Technologies utilisées

- PHP (PDO, sessions, password_hash / password_verify)
- MySQL
- HTML / CSS
- PHPMyAdmin
- WampServer
- VSCode

## Installation

1. Cloner le repository
   ```bash
   git clone https://github.com/ton-username/CONGRES_ANALIM.git
   ```

2. Copier le dossier dans le répertoire de WampServer
   ```
   C:\wamp64\www\CONGRES_ANALIM
   ```

3. Créer la base de données dans PHPMyAdmin
   - Ouvrir `http://localhost/phpmyadmin`
   - Créer une base nommée `congresanalim`
   - Importer le fichier `sql/jeu_donnees.sql`

4. Vérifier la configuration de connexion dans `app/core/Database.php`
   ```php
   private $host          = "localhost";
   private $database_name = "congresanalim";
   private $username      = "root";
   private $password      = "";
   ```

5. Accéder à l'application
   ```
   http://localhost/CONGRES_ANALIM
   ```

## Compte administrateur

Le compte organisateur correspond au congressiste dont l'`IDCongressiste` est **1** en base de données. Ce compte dispose des droits de gestion des activités et de consultation des listes de participants, mais ne peut pas s'inscrire aux activités en tant que participant.

## Auteur

**Sékou Mahamadou Zan DIARRA**  
Étudiant en BTS SIO option SLAM — Lycée Suzanne Valadon, Limoges
