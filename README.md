# 🚀 Gestion Ateliers - Backend Laravel

---

## 📄 Description

Backend Laravel pour la gestion des ateliers, des utilisateurs, des inscriptions avec authentification via Sanctum (token API).  
Base de données MySQL nommée `sg_atelier1`.

---

## 🛠️ Technologies utilisées

- Laravel 10
- PHP 8+
- MySQL (base `sg_atelier1`)
- Laravel Sanctum pour authentification API
- Eloquent ORM

---

## 🗄️ Base de données

Nom : `sg_atelier1`

Contient les tables principales :

- `utilisateurs` (id, nom, prenom, email, mot_de_passe, role, timestamps)
- `ateliers` (id, titre, description, date_debut, date_fin, lieu, formateur_id, timestamps)
- `inscriptions` (id, atelier_id, utilisateur_id, timestamps)

---

## 🚀 Installation et configuration

1. Cloner le dépôt backend :

```bash
git clone <URL_DU_DEPOT_BACKEND>
cd nom-du-projet-backend
Installer les dépendances composer :
```

```bash
Copier
Modifier
composer install
Copier le fichier .env.example en .env et configurer la connexion à la base MySQL :
```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sg_atelier1
DB_USERNAME=ton_utilisateur
DB_PASSWORD=ton_mot_de_passe
Générer la clé d’application :

```bash
php artisan key:generate
```
Lancer les migrations pour créer les tables :

```bash
php artisan migrate
```
(Optionnel) Lancer les seeders si disponibles :

```bash
php artisan db:seed
```
Lancer le serveur Laravel :

```bash
php artisan serve
L’API sera accessible par défaut sur http://localhost:8000.
```

🔐 Authentification
Utilisation de Laravel Sanctum pour la gestion des tokens API.

Routes protégées par middleware auth:sanctum et contrôle des rôles (admin, formateur, participant).

🚦 Routes principales
POST /api/register - Inscription utilisateur

POST /api/login - Connexion utilisateur

POST /api/logout - Déconnexion (authentifié)

GET /api/ateliers - Liste des ateliers

GET /api/ateliers/{id} - Détails atelier avec formateur et participants

POST /api/ateliers - Créer un atelier (admin/formateur)

PUT /api/ateliers/{id} - Modifier atelier (admin/formateur)

DELETE /api/ateliers/{id} - Supprimer atelier (admin/formateur)

GET /api/ateliers/{id}/participants - Liste des participants (admin/formateur)

GET /api/formateurs - Liste des formateurs (admin)

GET /api/utilisateurs - Gestion utilisateurs (admin)

POST /api/inscriptions - S’inscrire à un atelier (participant)

DELETE /api/inscriptions/atelier/{atelierId} - Désinscription (participant)

📚 Structure du projet
pgsql
Copier
Modifier
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── AtelierController.php
│   │   ├── UtilisateurController.php
│   │   └── InscriptionController.php
│   └── Middleware/
├── Models/
│   ├── Atelier.php
│   ├── Utilisateur.php
│   └── Inscription.php
database/
├── migrations/
└── seeders/
routes/
└── api.php
