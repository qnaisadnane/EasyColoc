EasyColoc 🏠

EasyColoc est une application web de gestion de colocation conçue pour simplifier l'organisation de la vie quotidienne entre colocataires. Elle permet de gérer les membres, les dépenses communes et la répartition des frais de manière simple et centralisée.

📋 Table des matières

Présentation

Fonctionnalités

Rôles

Technologies utilisées

Architecture du projet

Installation

Configuration

Utilisation

Base de données

Sécurité

Améliorations futures

Auteur

Licence

🎯 Présentation

La gestion d'une colocation peut rapidement devenir compliquée lorsqu'il faut suivre les dépenses communes, savoir qui a payé quoi et déterminer combien chaque colocataire doit rembourser.

EasyColoc propose une solution centralisée permettant aux colocataires de :

gérer les informations de la colocation ;

ajouter et suivre les dépenses communes ;

répartir les dépenses entre les membres ;

consulter les soldes et les montants dus ;

suivre l'historique des dépenses ;

gérer les membres de la colocation.

L'objectif est de rendre la gestion financière d'une colocation plus transparente, organisée et facile à utiliser.

✨ Fonctionnalités

👥 Gestion des utilisateurs

Inscription et connexion.

Authentification des utilisateurs.

Gestion du profil.

Gestion des membres d'une colocation.

Invitations de nouveaux membres.

🏠 Gestion de la colocation

Création d'une colocation.

Gestion des membres.

Consultation des informations de la colocation.

Gestion des dépenses liées à la colocation.

💰 Gestion des dépenses

Ajout d'une dépense.

Modification d'une dépense.

Suppression d'une dépense.

Consultation de l'historique.

Association d'une dépense à un ou plusieurs membres.

Calcul de la part de chaque colocataire.

⚖️ Répartition des dépenses

L'application permet de déterminer les montants à payer ou à recevoir pour chaque membre.

Exemple :

Alice paie 300 DH pour une dépense commune répartie entre 3 colocataires.
Chaque personne doit donc supporter 100 DH.
Alice doit recevoir 200 DH au total de la part des deux autres colocataires.

📊 Tableau de bord

Le tableau de bord permet d'avoir une vue globale sur :

les dépenses ;

le total dépensé ;

les montants payés par chaque membre ;

les montants à rembourser ;

les soldes des colocataires.

👤 Rôles

Administrateur / Responsable de la colocation

Il peut notamment :

créer et gérer une colocation ;

ajouter ou inviter des membres ;

gérer les informations de la colocation ;

consulter les dépenses ;

gérer les dépenses selon les permissions définies.

Colocataire

Il peut notamment :

consulter les informations de la colocation ;

ajouter des dépenses ;

consulter les dépenses ;

suivre ses paiements ;

consulter les montants qu'il doit ou qu'on lui doit.

Les permissions exactes peuvent être adaptées selon la configuration de l'application.

🛠 Technologies utilisées

Technologie

Utilisation

PHP

Langage backend

Laravel

Framework backend

Blade

Moteur de templates

Tailwind CSS

Interface utilisateur

JavaScript

Interactions côté client

MySQL

Base de données

Eloquent ORM

Interaction avec la base de données

Git

Gestion de versions

GitHub

Hébergement du code source

🏗 Architecture du projet

Le projet suit l'architecture MVC (Model-View-Controller) proposée par Laravel.

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

🚀 Installation

Prérequis

Avant de commencer, assurez-vous d'avoir installé :

PHP 8.2 ou une version compatible avec votre projet Laravel ;

Composer ;

MySQL ;

Node.js et npm ;

Git ;

un environnement local comme Laragon, XAMPP ou équivalent.

1. Cloner le projet

git clone https://github.com/votre-username/easycoloc.git
cd easycoloc

2. Installer les dépendances PHP

composer install

3. Installer les dépendances frontend

npm install

4. Créer le fichier .env

cp .env.example .env

Sous Windows, vous pouvez également copier manuellement .env.example et renommer la copie en .env.

5. Générer la clé de l'application

php artisan key:generate

6. Configurer la base de données

Créez une base de données MySQL, puis configurez les informations suivantes dans .env :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easycoloc
DB_USERNAME=root
DB_PASSWORD=

Adaptez les valeurs à votre environnement local.

7. Exécuter les migrations

php artisan migrate

Si le projet contient des seeders :

php artisan db:seed

Ou :

php artisan migrate --seed

8. Compiler les assets

Pour le développement :

npm run dev

9. Démarrer l'application

Dans un autre terminal :

php artisan serve

L'application sera généralement accessible à l'adresse :

http://127.0.0.1:8000

⚙️ Configuration

Le fichier .env contient les principales variables de configuration.

Exemple :

APP_NAME=EasyColoc
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easycoloc
DB_USERNAME=root
DB_PASSWORD=

Important : ne partagez jamais votre fichier .env, car il peut contenir des informations sensibles.

🗄️ Base de données

La base de données repose sur MySQL et utilise Eloquent ORM pour gérer les relations entre les différentes entités.

Les principales entités du système peuvent inclure :

User
  │
  └── Colocation
          │
          ├── Members
          │
          └── Expenses
                  │
                  └── Expense Shares

Les relations permettent notamment de :

associer des utilisateurs à une colocation ;

associer une dépense à son créateur ;

associer une dépense aux membres concernés ;

calculer la répartition des montants.

🔐 Sécurité

EasyColoc applique les mécanismes de sécurité fournis par Laravel, notamment :

authentification des utilisateurs ;

protection CSRF ;

validation des données ;

utilisation de l'ORM Eloquent ;

protection contre les injections SQL grâce aux requêtes paramétrées ;

gestion sécurisée des mots de passe ;

contrôle des accès selon les rôles et permissions.

🔮 Améliorations futures

Plusieurs fonctionnalités peuvent être ajoutées dans les prochaines versions :

📱 Application mobile avec Flutter ;

🔔 notifications en temps réel ;

💳 intégration de paiements en ligne ;

📈 statistiques financières plus avancées ;

📄 génération de rapports PDF ;

📧 notifications par email ;

🔄 synchronisation automatique des remboursements ;

🌍 support multilingue ;

🤖 assistant intelligent pour analyser les dépenses ;

☁️ déploiement cloud et architecture SaaS.

📸 Captures d'écran

Ajoutez ici les captures d'écran de votre application :

docs/
├── dashboard.png
├── expenses.png
├── members.png
└── profile.png

Exemple :

![Dashboard](docs/dashboard.png)

👨‍💻 Auteur

ADNANE QNAIS

Full Stack Developer

GitHub : https://github.com/votre-username

LinkedIn : https://www.linkedin.com/in/votre-profile

📄 Licence

Ce projet est un projet personnel / académique développé à des fins d'apprentissage et de démonstration.

⭐ Si ce projet vous plaît, n'hésitez pas à lui donner une étoile sur GitHub !
