# Application de Gestion des Employés

Cette application PHP permet de gérer les employés d'une entreprise avec différents types (Admin, Manager, Développeur).

## Installation

1. **Base de données** : Créez la base de données en exécutant le script `create_database.sql` dans votre serveur MySQL
2. **Configuration** : Modifiez les paramètres de connexion dans `Database.php` si nécessaire
3. **Exécution** : Lancez l'application avec `php app.php`

## Structure du projet

- `entity/` : Classes des entités (Employee, Admin, Manager, Developpeur, Service, etc.)
- `repository/` : Classes d'accès aux données
- `service/` : Classes de logique métier
- `app.php` : Point d'entrée de l'application
- `Database.php` : Gestion de la connexion à la base de données

## Fonctionnalités

- Création d'employés (Admin, Manager, Développeur)
- Gestion des services
- Calcul des salaires
- Statistiques des employés
- Assignation des employés aux services

## Types d'employés

- **Admin** : Salaire fixe de 4000€
- **Manager** : Salaire de base 2000€ + prime
- **Développeur** : Salaire fixe de 1800€

## Spécialités des développeurs

- FullStack (FS)
- FrontEnd (FE)
- BackEnd (BE)