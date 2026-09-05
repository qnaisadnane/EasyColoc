# EasyColoc 🏠

EasyColoc est une application web de gestion de colocation conçue pour simplifier l'organisation de la vie quotidienne entre colocataires. Elle permet de gérer les membres, les dépenses communes et la répartition des frais de manière simple et centralisée.

## 📋 Table des matières

- [Présentation](#-présentation)
- [Fonctionnalités](#-fonctionnalités)
- [Rôles](#-rôles)
- [Technologies utilisées](#-technologies-utilisées)
- [Architecture du projet](#-architecture-du-projet)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Base de données](#-base-de-données)
- [Sécurité](#-sécurité)
- [Améliorations futures](#-améliorations-futures)
- [Auteur](#-auteur)
- [Licence](#-licence)

## 🎯 Présentation

La gestion d'une colocation peut rapidement devenir compliquée lorsqu'il faut suivre les dépenses communes, savoir qui a payé quoi et déterminer combien chaque colocataire doit rembourser.

**EasyColoc** propose une solution centralisée permettant aux colocataires de :

- gérer les informations de la colocation ;
- ajouter et suivre les dépenses communes ;
- répartir les dépenses entre les membres ;
- consulter les soldes et les montants dus ;
- suivre l'historique des dépenses ;
- gérer les membres de la colocation.

L'objectif est de rendre la gestion financière d'une colocation plus transparente, organisée et facile à utiliser.

## ✨ Fonctionnalités

### 👥 Gestion des utilisateurs

- Inscription et connexion
- Authentification des utilisateurs
- Gestion du profil
- Gestion des membres d'une colocation
- Invitations de nouveaux membres

### 🏠 Gestion de la colocation

- Création d'une colocation
- Gestion des membres
- Consultation des informations de la colocation
- Gestion des dépenses liées à la colocation

### 💰 Gestion des dépenses

- Ajouter une dépense
- Modifier une dépense
- Supprimer une dépense
- Consulter l'historique des dépenses
- Associer une dépense à un ou plusieurs membres
- Calculer automatiquement la part de chaque colocataire

### ⚖️ Répartition des dépenses

L'application permet de déterminer les montants à payer ou à recevoir pour chaque membre.

**Exemple :**

> Alice paie 300 DH pour une dépense commune répartie entre 3 colocataires.  
> Chaque personne doit donc supporter 100 DH.  
> Alice doit recevoir 200 DH au total de la part des deux autres colocataires.

### 📊 Tableau de bord

Le tableau de bord permet d'avoir une vue globale sur :

- les dépenses ;
- le total dépensé ;
- les montants payés par chaque membre ;
- les montants à rembourser ;
- les soldes des colocataires.

## 👤 Rôles

### Administrateur / Responsable de la colocation

Il peut notamment :

- créer et gérer une colocation ;
- ajouter ou inviter des membres ;
- gérer les informations de la colocation ;
- consulter les dépenses ;
- gérer les dépenses selon les permissions définies.

### Colocataire

Il peut notamment :

- consulter les informations de la colocation ;
- ajouter des dépenses ;
- consulter les dépenses ;
- suivre ses paiements ;
- consulter les montants qu'il doit ou qu'on lui doit.

## 🛠 Technologies utilisées

| Technologie | Utilisation |
|---|---|
| PHP | Langage backend |
| Laravel | Framework backend |
| Blade | Moteur de templates |
| Tailwind CSS | Interface utilisateur |
| JavaScript | Interactions côté client |
| MySQL | Base de données |
| Eloquent ORM | Interaction avec la base de données |
| Git | Gestion de versions |
| GitHub | Hébergement du code source |

## 🏗 Architecture du projet

Le projet suit l'architecture **MVC (Model-View-Controller)** proposée par Laravel.

```text
EasyColoc/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php
│   └── ...
├── public/
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
└── README.md
