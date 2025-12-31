# La Fine Équipe

![La Fine Équipe Logo](https://github.com/user-attachments/assets/de09a229-06a5-43fb-878c-e8544b1486c5)

**La Fine Équipe** est une plateforme web de gestion d'ateliers et d'activités pour une association dédiée au soutien des jeunes en souffrance psychique et de leurs proches.

## 📋 Table des matières

- [À propos](#-à-propos)
- [Fonctionnalités](#-fonctionnalités)
- [Technologies utilisées](#-technologies-utilisées)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Structure du projet](#-structure-du-projet)
- [Captures d'écran](#-captures-décran)
- [Contribution](#-contribution)
- [Licence](#-licence)

## 🎯 À propos

La Fine Équipe est une association créée en 2024 au Mans (72), France. Notre mission est de redonner de la joie de vivre à des jeunes en souffrance psychique et à leurs proches en organisant régulièrement des activités favorisant la convivialité, le partage et la réhabilitation sociale.

Cette application web permet de :
- Gérer les ateliers et activités de l'association
- Faciliter l'inscription des participants aux différents événements
- Organiser et catégoriser les ateliers
- Communiquer les informations importantes aux membres

## ✨ Fonctionnalités

### Pour les utilisateurs
- 🔐 **Inscription et authentification** avec vérification par email
- 👤 **Profil personnalisé** avec avatar et informations de contact
- 📅 **Consultation des ateliers** disponibles avec filtrage par catégorie
- ✅ **Inscription/désinscription** aux ateliers (avec gestion des places limitées)
- 📊 **Suivi des inscriptions** personnelles
- 📧 **Formulaire de contact** pour communiquer avec l'association
- 🖼️ **Galerie d'images** pour chaque atelier

### Pour les administrateurs
- ➕ **Création et gestion des ateliers** (CRUD complet)
- 🏷️ **Gestion des catégories** d'ateliers
- 📸 **Upload d'images** pour les ateliers
- 👥 **Visualisation des participants** inscrits
- 🎨 **Personnalisation** des ateliers avec descriptions détaillées

### Fonctionnalités techniques
- 🔒 **Sécurité renforcée** avec gestion des rôles (USER, ADMIN)
- 📱 **Interface responsive** compatible mobile et desktop
- ⚡ **Performance optimisée** avec Webpack Encore
- 🎨 **Design moderne** avec Bootstrap 5
- ♿ **Accessibilité** prise en compte dans les formulaires

## 🛠️ Technologies utilisées

### Backend
- **PHP 8.2+** - Langage de programmation
- **Symfony 7.2** - Framework PHP
- **Doctrine ORM** - Gestion de la base de données
- **Twig** - Moteur de templates

### Frontend
- **HTML5/CSS3** - Structure et style
- **Bootstrap 5.3** - Framework CSS responsive
- **JavaScript** - Interactivité
- **Webpack Encore** - Bundler d'assets
- **Stimulus** - Framework JavaScript léger
- **Turbo** - Navigation SPA-like

### Base de données
- **PostgreSQL 16** - Base de données relationnelle

### DevOps
- **Docker** - Conteneurisation
- **Docker Compose** - Orchestration des conteneurs
- **Git** - Gestion de versions

## 📦 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP >= 8.2** avec les extensions suivantes :
  - `ctype`
  - `iconv`
  - `pdo_pgsql`
- **Composer** - Gestionnaire de dépendances PHP
- **Node.js >= 18** et **npm** - Pour la compilation des assets
- **PostgreSQL >= 16** - Base de données (ou Docker)
- **Docker et Docker Compose** (optionnel mais recommandé)

## 🚀 Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/LouisGastineau/LaFineEquipe.git
cd LaFineEquipe
```

### 2. Installation avec Docker (Recommandé)

```bash
# Démarrer la base de données
docker compose up -d

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Compiler les assets
npm run build

# Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# (Optionnel) Charger des données de test
php bin/console doctrine:fixtures:load
```

### 3. Installation manuelle (sans Docker)

Si vous n'utilisez pas Docker, assurez-vous d'avoir PostgreSQL installé et configuré localement.

```bash
# Installer les dépendances
composer install
npm install

# Compiler les assets
npm run build

# Configurer la base de données dans .env.local
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/lafineequipe?serverVersion=16&charset=utf8"

# Créer la base de données et les tables
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## ⚙️ Configuration

### Variables d'environnement

Créez un fichier `.env.local` à la racine du projet et configurez les variables suivantes :

```env
# Environnement
APP_ENV=dev
APP_SECRET=your-secret-key-here

# Base de données
DATABASE_URL="postgresql://app:!ChangeMe!@database:5432/app?serverVersion=16&charset=utf8"

# Mailer (pour l'envoi d'emails)
MAILER_DSN=smtp://mailer:1025
```

### Créer un utilisateur administrateur

```bash
# Via la console Symfony
php bin/console app:create-admin

# Ou créer un utilisateur normal puis modifier son rôle en base
# UPDATE "user" SET roles = '["ROLE_ADMIN"]' WHERE email = 'admin@example.com';
```

## 💻 Utilisation

### Démarrer l'application

#### Avec Docker
```bash
# Démarrer tous les services
docker compose up -d

# Démarrer le serveur Symfony
symfony server:start
# ou
php -S localhost:8000 -t public/
```

#### En mode développement
```bash
# Terminal 1 : Démarrer le serveur Symfony
symfony server:start

# Terminal 2 : Compiler les assets en mode watch
npm run watch
```

L'application sera accessible à l'adresse : **http://localhost:8000**

### Commandes utiles

```bash
# Compiler les assets pour la production
npm run build

# Lancer les tests
php bin/phpunit

# Vider le cache
php bin/console cache:clear

# Créer une migration
php bin/console make:migration

# Créer un nouveau controller
php bin/console make:controller
```

## 📁 Structure du projet

```
LaFineEquipe/
├── assets/                 # Assets frontend (JS, CSS, images)
│   ├── imgs/              # Images statiques
│   └── js/                # Fichiers JavaScript
├── bin/                   # Scripts exécutables
├── config/                # Configuration Symfony
│   ├── packages/         # Configuration des bundles
│   └── routes/           # Configuration des routes
├── migrations/            # Migrations de base de données
├── public/                # Fichiers publics accessibles
│   ├── uploads/          # Fichiers uploadés (avatars, images d'ateliers)
│   └── build/            # Assets compilés
├── src/                   # Code source PHP
│   ├── Controller/       # Contrôleurs
│   ├── Entity/           # Entités Doctrine
│   ├── Form/             # Formulaires Symfony
│   ├── Repository/       # Repositories Doctrine
│   ├── Security/         # Services de sécurité
│   └── Twig/             # Extensions Twig
├── templates/             # Templates Twig
│   ├── category/         # Templates pour les catégories
│   ├── main/             # Pages principales
│   ├── registration/     # Inscription
│   ├── security/         # Authentification
│   ├── user/             # Profils utilisateurs
│   └── workshop/         # Ateliers
├── tests/                 # Tests unitaires et fonctionnels
├── translations/          # Fichiers de traduction
├── composer.json          # Dépendances PHP
├── package.json           # Dépendances JavaScript
├── webpack.config.js      # Configuration Webpack
└── docker-compose.yml     # Configuration Docker
```

## 📸 Captures d'écran

### Équipe de l'association
![Équipe La Fine Équipe](https://github.com/user-attachments/assets/f9f65f1d-327c-419d-8d3c-512bad347836)

*L'équipe de bénévoles et participants lors d'un atelier en plein air*

### Page d'accueil
La page d'accueil présente la mission de l'association et les événements à venir. Elle offre une navigation intuitive vers les différentes sections du site.

**Fonctionnalités visibles :**
- Bannière d'accueil avec le message de l'association
- Section "Notre Mission" détaillant les objectifs
- Liste des événements à venir
- Navigation responsive

### Liste des ateliers
Les utilisateurs peuvent consulter tous les ateliers disponibles, avec possibilité de filtrer par catégorie.

**Fonctionnalités visibles :**
- Cartes d'ateliers avec images
- Badges de catégories
- Indicateur de places disponibles/complètes
- Boutons d'inscription/désinscription
- Filtrage par catégorie

### Détails d'un atelier
Chaque atelier dispose d'une page dédiée avec toutes les informations nécessaires.

**Fonctionnalités visibles :**
- Image de l'atelier en grand format
- Description complète
- Date et heure
- Liste des participants inscrits avec avatars
- Gestion de la capacité (places restantes)
- Bouton d'inscription si places disponibles

### Profil utilisateur
Chaque membre dispose d'un profil personnalisé pour suivre ses inscriptions.

**Fonctionnalités visibles :**
- Avatar personnalisable
- Informations de contact (privées)
- Liste des ateliers auxquels l'utilisateur est inscrit
- Possibilité d'éditer son profil

### Formulaire de contact
Une page de contact accessible permet de communiquer facilement avec l'association.

**Fonctionnalités visibles :**
- Formulaire avec validation
- Différents sujets de contact
- Informations de l'association (adresse, email)
- Design accessible et responsive

## 🤝 Contribution

Les contributions sont les bienvenues ! Si vous souhaitez contribuer au projet :

1. **Forkez** le projet
2. **Créez** votre branche de fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. **Committez** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. **Poussez** vers la branche (`git push origin feature/AmazingFeature`)
5. **Ouvrez** une Pull Request

### Standards de code
- Respecter les standards **PSR-12** pour PHP
- Utiliser les conventions de nommage **Symfony**
- Commenter le code complexe
- Écrire des tests pour les nouvelles fonctionnalités

## 📄 Licence

Ce projet est sous licence propriétaire. Tous droits réservés.

## 📞 Contact

**La Fine Équipe**
- 📍 Adresse : Le Mans (72), France
- 📧 Email : contact@lafineequipe.org
- 🌐 Site web : [En construction]

---

Développé avec ❤️ par l'équipe de La Fine Équipe pour favoriser le lien social et l'entraide.
