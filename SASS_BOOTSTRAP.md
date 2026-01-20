# Configuration Bootstrap + Sass

## Installation

### 1. Installer Node.js
Si ce n'est pas déjà fait, téléchargez et installez Node.js depuis https://nodejs.org/

### 2. Installer les dépendances Sass
Ouvrez un terminal dans le dossier du projet et exécutez :
```bash
npm install
```

## Compilation Sass

### Compilation unique
```bash
npm run sass
```

### Mode watch (compilation automatique)
Pour compiler automatiquement à chaque modification :
```bash
npm run sass:watch
```

### Compilation minifiée (production)
```bash
npm run sass:build
```

## Structure Sass

```
public/
├── scss/
│   ├── _variables.scss    # Variables (couleurs, espacements, etc.)
│   ├── _mixins.scss       # Mixins réutilisables
│   └── style.scss         # Fichier principal
└── css/
    └── style.css          # CSS compilé (ne pas modifier directement)
```

## Bootstrap 5

Bootstrap est intégré via CDN dans les vues HTML.

### CDN utilisé :
- CSS : https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css
- JS : https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js

### Classes Bootstrap disponibles :
- Layout : container, row, col-*
- Composants : btn, card, modal, navbar, form-control, etc.
- Utilitaires : mt-*, mb-*, p-*, d-flex, text-center, etc.

## Utilisation

### Dans vos vues PHP :
1. Le CDN Bootstrap est déjà inclus
2. Le fichier `style.css` compilé est chargé après Bootstrap
3. Vos styles personnalisés (Sass) surchargent Bootstrap

### Modifier les styles :
1. Éditez les fichiers `.scss` dans `public/scss/`
2. Lancez `npm run sass:watch` pour compiler automatiquement
3. Les modifications apparaîtront dans `public/css/style.css`

## Variables personnalisables

Éditez `public/scss/_variables.scss` pour personnaliser :
- Couleurs
- Espacements
- Typographie
- Breakpoints responsive
