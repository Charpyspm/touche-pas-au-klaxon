# Touche pas au klaxon

Application de covoiturage intra-entreprise développée en PHP avec architecture MVC.

## 📁 Architecture MVC

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
│   └── Trajet.php             # Modèle trajet
├── views/
│   ├── home.php               # Page d'accueil (liste des trajets)
│   ├── auth/
│   │   └── login.php          # Formulaire de connexion
│   └── trajet/
│       ├── create.php         # Formulaire de création
│       └── edit.php           # Formulaire de modification
├── public/
│   └── css/
│       └── style.css          # Feuilles de style
└── SQL/
    ├── database_schema.sql    # Structure de la base
    └── database_seed.sql      # Données de test

```

## 🎯 Fonctionnalités

### Authentification
- ✅ Connexion par email uniquement (sans mot de passe)
- ✅ Gestion des sessions utilisateur
- ✅ Déconnexion

### Gestion des trajets
- ✅ Création de trajets (agences, dates, horaires, places)
- ✅ Modification de ses propres trajets
- ✅ Suppression de ses propres trajets
- ✅ Visualisation des détails via modal popup
- ✅ Liste de tous les trajets avec filtrage par permissions

### Interface
- Design responsive
- Modal popup pour les détails
- Icônes d'actions conditionnelles (modifier/supprimer pour ses trajets, voir pour les autres)
- Messages de succès/erreur

## 🔗 Routes (Pattern MVC)

| Route | Méthode | Contrôleur | Action | Description |
|-------|---------|------------|--------|-------------|
| `/?action=home` | GET | TrajetController | index() | Page d'accueil |
| `/?action=login` | GET/POST | AuthController | showLoginForm() / login() | Connexion |
| `/?action=logout` | GET | AuthController | logout() | Déconnexion |
| `/?action=create` | GET | TrajetController | create() | Formulaire création |
| `/?action=store` | POST | TrajetController | store() | Enregistrer trajet |
| `/?action=edit&id=X` | GET | TrajetController | edit() | Formulaire modification |
| `/?action=update` | POST | TrajetController | update() | Mettre à jour trajet |
| `/?action=delete&id=X` | GET | TrajetController | delete() | Supprimer trajet |
| `/?action=details&id=X` | GET | TrajetController | details() | API JSON détails |

## 🗄️ Base de données

### Tables principales
- **users** : Employés de l'entreprise
- **agences** : Villes/sites de l'entreprise
- **trajets** : Covoiturages proposés

### Relations
- Un trajet appartient à un utilisateur (conducteur)
- Un trajet a une agence de départ et une agence d'arrivée

## 🚀 Installation

1. Placez le projet dans `C:\wamp64\www\touche-pas-au-klaxon`
2. Importez `SQL/database_schema.sql` dans phpMyAdmin
3. Importez `SQL/database_seed.sql` pour les données de test
4. Accédez à `http://localhost/touche-pas-au-klaxon`

## 🔧 Configuration

La configuration de la base de données se trouve dans `config/database.php` :
- Hôte : localhost
- Base : touche_pas_au_klaxon
- Utilisateur : root
- Mot de passe : (vide)

## 📝 Pattern MVC appliqué

### Model (Modèle)
- Gère l'accès aux données
- Interaction avec la base de données via PDO
- Classes : `User`, `Trajet`

### View (Vue)
- Présentation pure (HTML/CSS)
- Pas de logique métier
- Affichage des données passées par le contrôleur

### Controller (Contrôleur)
- Logique métier
- Traitement des requêtes
- Validation des données
- Appel des modèles et des vues
- Classes : `AuthController`, `TrajetController`

## 🛠️ Technologies

- **Backend** : PHP 7.4+
- **Base de données** : MySQL via PDO
- **Frontend** : 
  - HTML5, CSS3, JavaScript (vanilla)
  - **Bootstrap 5** (via CDN) - Framework CSS responsive
  - **Bootstrap Icons** - Bibliothèque d'icônes
  - **Sass** (SCSS) - Préprocesseur CSS
- **Serveur** : WAMP Server
- **Architecture** : MVC (Model-View-Controller)
- **Outils de build** : npm, Sass compiler

## 📦 Installation complète

### Prérequis
- WAMP Server
- Node.js (pour Sass)

### Étapes

1. **Cloner/Placer le projet**
   ```
   C:\wamp64\www\touche-pas-au-klaxon
   ```

2. **Base de données**
   - Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
   - Importer `SQL/database_schema.sql`
   - Importer `SQL/database_seed.sql`

3. **Installer les dépendances Node.js**
   ```bash
   npm install
   ```

4. **Compiler le Sass**
   ```bash
   npm run sass
   # Ou pour le mode watch (auto-compilation)
   npm run sass:watch
   ```

5. **Accéder à l'application**
   ```
   http://localhost/touche-pas-au-klaxon
   ```

## 🎨 Styles et Design

### Bootstrap 5
- Intégré via CDN
- Classes utilitaires disponibles
- Composants responsive (grilles, boutons, modals, etc.)
- Documentation : https://getbootstrap.com/

### Sass (SCSS)
Structure des fichiers :
```
public/
├── scss/
│   ├── _variables.scss    # Variables personnalisables
│   ├── _mixins.scss       # Mixins réutilisables
│   └── style.scss         # Fichier principal
└── css/
    └── style.css          # CSS compilé (généré automatiquement)
```

**Commandes Sass :**
- `npm run sass` - Compilation unique
- `npm run sass:watch` - Mode watch (auto-compilation)
- `npm run sass:build` - Compilation minifiée (production)

**Note :** Modifiez uniquement les fichiers `.scss`, jamais le `style.css` directement !

---

© 2024 - CENEF - MVC PHP
