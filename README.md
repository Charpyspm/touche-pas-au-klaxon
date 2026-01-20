# Touche pas au klaxon

Application de covoiturage intra-entreprise développée en PHP avec architecture MVC.

## Architecture du projet

```
touche-pas-au-klaxon/
├── index.php                    # Routeur principal (Front Controller)
├── config/
│   └── database.php            # Configuration de la base de données
├── controllers/
│   ├── AuthController.php      # Gestion de l'authentification
│   └── TrajetController.php    # Gestion des trajets (CRUD)
├── models/
│   ├── User.php               # Modèle utilisateur
│   ├── Agence.php             # Modèle agence
│   └── Trajet.php             # Modèle trajet
├── views/
│   ├── home.php               # Page d'accueil (liste des trajets)
│   ├── auth/
│   │   └── login.php          # Formulaire de connexion
│   └── trajet/
│       ├── create.php         # Formulaire de création
│       └── edit.php           # Formulaire de modification
├── public/
│   ├── scss/                  # Sources Sass
│   └── css/
│       └── style.css          # Feuilles de style compilées
├── tests/                     # Tests unitaires PHPUnit
├── SQL/
│   ├── database_schema.sql    # Structure de la base
│   └── database_seed.sql      # Données de test
├── composer.json              # Dépendances PHP
├── phpunit.xml                # Configuration PHPUnit
└── phpstan.neon               # Configuration PHPStan
```

## Fonctionnalités

### Authentification
- Connexion par email et mot de passe
- Gestion des sessions utilisateur
- Rôles : administrateur et employé
- Déconnexion sécurisée

### Gestion des trajets
- Création de trajets avec sélection d'agences, dates, horaires et nombre de places
- Modification de ses propres trajets
- Suppression de ses propres trajets
- Visualisation des détails via modal
- Contrôles de cohérence : agences différentes, dates valides
- Filtrage automatique : seuls les trajets avec places disponibles s'affichent sur l'accueil
- Gestion des trajets complets (0 places disponibles)

### Interface administrateur
- Liste complète des utilisateurs
- Gestion CRUD des agences
- Visualisation et suppression de tous les trajets
- Interface dédiée accessible uniquement aux administrateurs

### Interface utilisateur
- Design responsive avec Bootstrap 5
- Modal popup pour les détails des trajets
- Icônes d'actions conditionnelles selon les permissions
- Messages de succès et d'erreur contextuels

## Routes principales

| Route | Méthode | Contrôleur | Action | Description |
|-------|---------|------------|--------|-------------|
| `/?action=home` | GET | TrajetController | index() | Page d'accueil |
| `/?action=login` | GET/POST | AuthController | showLoginForm() / login() | Connexion |
| `/?action=logout` | GET | AuthController | logout() | Déconnexion |
| `/?action=create` | GET | TrajetController | create() | Formulaire de création |
| `/?action=store` | POST | TrajetController | store() | Enregistrer un trajet |
| `/?action=edit&id=X` | GET | TrajetController | edit() | Formulaire de modification |
| `/?action=update` | POST | TrajetController | update() | Mettre à jour un trajet |
| `/?action=delete&id=X` | GET | TrajetController | delete() | Supprimer un trajet |
| `/?action=details&id=X` | GET | TrajetController | details() | API JSON pour les détails |
| `/?action=users` | GET | - | - | API JSON liste utilisateurs (admin) |
| `/?action=agences` | GET | - | - | API JSON liste agences (admin) |
| `/?action=trajets_admin` | GET | - | - | API JSON tous les trajets (admin) |

## Base de données

### Tables
- **users** : Employés et administrateurs avec authentification
- **agences** : Sites de l'entreprise
- **trajets** : Covoiturages proposés avec places disponibles

### Relations et contraintes
- Un trajet appartient à un utilisateur (conducteur)
- Un trajet a une agence de départ et une agence d'arrivée distinctes
- La date d'arrivée doit être postérieure à la date de départ
- Le nombre de places disponibles ne peut excéder le total
- Une agence ne peut être supprimée si elle est utilisée dans des trajets

## Installation

### Prérequis
- WAMP Server (ou Apache + MySQL + PHP 7.4+)
- Node.js et npm
- Composer

### Étapes d'installation

1. Cloner le projet dans le répertoire web
   ```
   C:\wamp64\www\touche-pas-au-klaxon
   ```

2. Créer et configurer la base de données
   - Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
   - Créer la base `touche_pas_au_klaxon`
   - Importer `SQL/database_schema.sql`
   - Importer `SQL/database_seed.sql`

3. Installer les dépendances PHP
   ```bash
   php composer.phar install
   ```

4. Installer les dépendances Node.js
   ```bash
   npm install
   ```

5. Compiler les fichiers Sass
   ```bash
   npm run sass
   ```

6. Accéder à l'application
   ```
   http://localhost/touche-pas-au-klaxon
   ```

### Comptes de test

Après l'import de `database_seed.sql` :

- **Administrateur** : admin@admin.fr / admin123
- **Employés** : email selon database_seed.sql / mot de passe = première lettre du prénom + première lettre du nom (ex: Alexandre Martin = am)

## Configuration

La configuration de la base se trouve dans `config/database.php`. Pour les tests, les variables d'environnement sont définies dans `phpunit.xml` et utilisent une base dédiée `touche_pas_au_klaxon_test`.

Configuration par défaut :
- Hôte : localhost
- Base : touche_pas_au_klaxon
- Utilisateur : root
- Mot de passe : (vide)

## Architecture MVC

### Model (Modèle)
Les modèles gèrent l'accès aux données et les interactions avec la base de données via PDO.

- `User.php` : Gestion des utilisateurs
- `Agence.php` : CRUD complet des agences avec vérification d'utilisation
- `Trajet.php` : Récupération des trajets avec filtrage selon disponibilité

### View (Vue)
Les vues sont responsables uniquement de l'affichage. Elles reçoivent les données des contrôleurs et les présentent en HTML/CSS sans logique métier.

### Controller (Contrôleur)
Les contrôleurs orchestrent la logique applicative.

- `AuthController.php` : Authentification et sessions
- `TrajetController.php` : CRUD des trajets avec validations métier

Le front controller (`index.php`) route les requêtes vers le bon contrôleur.

## Technologies utilisées

### Backend
- PHP 8.3 avec PDO pour l'accès aux données
- Architecture MVC stricte
- Sessions pour l'authentification

### Frontend
- HTML5 et CSS3
- JavaScript vanilla pour les interactions
- Bootstrap 5.3.0 (via CDN) pour le design responsive
- Bootstrap Icons 1.11.0 pour les icônes
- Sass (SCSS) pour le préprocessing CSS

### Outils de qualité
- **PHPStan** : Analyse statique du code (niveau 6)
- **PHPUnit** : Tests unitaires avec couverture des opérations d'écriture
- Documentation complète avec DocBlock

### Développement
- Sass pour la compilation CSS
- npm pour la gestion des dépendances frontend
- Composer pour les dépendances PHP

## Scripts npm disponibles

```bash
npm run sass          # Compilation Sass une fois
npm run sass:watch    # Mode watch avec recompilation automatique
npm run sass:build    # Compilation minifiée pour la production
```

Important : ne jamais modifier directement le fichier `style.css`, toujours passer par les fichiers SCSS.

## Tests unitaires

Le projet inclut une suite de tests PHPUnit couvrant les opérations d'écriture en base de données.

### Exécution des tests
```bash
php vendor/bin/phpunit              # Tous les tests
php vendor/bin/phpunit --testdox    # Format lisible
php vendor/bin/phpunit tests/AgenceTest.php  # Tests spécifiques
```

### Couverture
Les tests valident :
- Création, modification et suppression des agences
- Protection contre la suppression d'agences utilisées
- Création, modification et suppression des trajets
- Gestion des places disponibles (y compris 0)
- Contrôle des permissions utilisateur

La base de test `touche_pas_au_klaxon_test` est créée automatiquement et nettoyée après chaque test.

## Analyse de code

Le projet utilise PHPStan pour garantir la qualité du code.

```bash
php vendor/bin/phpstan analyse
```

Configuration niveau 6 avec typage strict des méthodes et propriétés.

## Structure Sass

```
public/scss/
├── _variables.scss    # Variables de couleurs, tailles, etc.
├── _mixins.scss       # Mixins réutilisables
└── style.scss         # Point d'entrée principal
```

Le fichier compilé `public/css/style.css` est généré automatiquement et ne doit pas être édité manuellement.

---

Projet développé dans le cadre de la formation CENEF
