# OmdbFlix - Films et Séries

## Projet Symfony

OmdbFlix est un site web dédié au cinéma et au série, développé en utilisant :  
- PHP avec le framework Symfony
- JavaScript pour une expérience utilisateur dynamique
- Une base de données MariaDB pour stocker les films, séries, commentaires et utilisateurs
  
Ce projet, réalisé dans le cadre de ma formation, vise à fournir une plateforme complète pour les amateurs de cinéma et séries.

## Mise en place

### Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants sur votre machine :

[Composer](https://getcomposer.org/download/)  
[PHP](https://www.php.net/manual/fr/install.php)

### Installation

- Cloner le répo
- Ajouter l'URL de la data base `DATABASE_URL` dans le fichier `.env.local`  et le remplir avec vos informations
- Ajouter `MAILER_DSN` dans le fichier `.env.local` pour avoir accès au service mailjet-mailer  et le remplir avec vos informations
- Ajouter `JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem` `JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem` `JWT_PASSPHRASE=` dans le fichier `.env.local` pour avoir accès au service JWT authentication  et le remplir avec vos informations
- Ajouter `CORS_ALLOW_ORIGIN` dans le fichier `.env.local` pour configurer le CORS  et le remplir avec vos informations
- Ajouter `API_KEY` dans le fichier `.env.local` pour avoir accès au service omdbapiService  et le remplir avec vos informations
- Ajouter `SENDER` dans le fichier `.env.local` pour avoir accès au service myMailerService  et le remplir avec vos informations
- Ajouter `MAINTENANCE_MSG` dans le fichier `.env.local` pour avoir accès au message de maintenance si un message est renseigné  et le remplir avec vos informations
- `composer install`
- `php bin/console doctrine:database:create`
- Importer le fichier `public/sql/omdb_flix.sql`
- Afin de récupérer les comptes de test, vous pouvez trouver les informations dans les DataFixtures, puis si nécessaire en créer de nouveaux
