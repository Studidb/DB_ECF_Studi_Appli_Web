# DB_ECF_Studi_Appli_Web
Projet d'application Web dans le cadre de mes études chez Studi (lié à l'évaluation en cours de formation)

# Prérequis pour la Configuration du Site Web

Pour configurer correctement le site web, assurez-vous de disposer de tous les prérequis nécessaires, tant en termes d'environnement que de logiciels. Voici les éléments à préparer avant de commencer :

## 1. Environnement de Travail
- **Windows** : Installez un environnement de serveur local tel que **WAMP** (Windows, Apache, MySQL, PHP).
- **Linux** : Utilisez un environnement **LAMP** (Linux, Apache, MySQL, PHP).
- **macOS** : Optez pour **MAMP**.
- **PHP** : Assurez-vous d'avoir une version compatible de PHP installée (version recommandée : 7.4 ou ultérieure).
- **phpMyAdmin** : Nécessaire pour gérer les bases de données MySQL.

## 2. Configuration de la Base de Données MySQL
- **Lancer les serveurs Apache et MySQL**.
- Accédez au **panneau d'administration de phpMyAdmin** via votre navigateur (`http://localhost/phpmyadmin`). Vous pouvez également gérer la base de données via la **ligne de commande**.
- **Créer la base de données** : `u386540360_4rcadiaadmin`.
- **Importer les fichiers SQL** : Importez les fichiers dans l'ordre suivant pour reconstituer la base de données :
  1. `u386540360_4rcadiaAdmin (1).sql`
  2. `u386540360_4rcadiaAdmin (2).sql`
  3. `u386540360_4rcadiaAdmin (3).sql`
- Il existe également une version **All-in-One** (`u386540360_4rcadiaAdmin ALLINONE.sql`) qui contient toutes les tables en un seul fichier. Utilisez cette version si votre environnement le permet. En cas de limitations d'importation (fréquentes avec phpMyAdmin), utilisez les trois fichiers séparés.

## 3. Contenu du Dossier Partagé
Le dossier partagé contient plusieurs fichiers et dossiers essentiels :

1. **Fichier "Perspective d'Amélioration"** : Document détaillant les leçons tirées du développement du site, les ajustements envisagés et les pistes d'amélioration pour une future version.
2. **Fichier "Accès Utilisateur"** : Contient les informations d'accès pour les **trois types de profils** nécessaires pour consulter le site dans son intégralité :
   - **Employé**
   - **Vétérinaire**
   - **Administrateur** (réservé à José)
3. **Fichier "Accès Github"** : Lien vers le dépôt GitHub, qui est actuellement public.
4. **Dossier "La Base de données à importer dans phpMyAdmin"** : Contient deux versions des tables de la base de données - une version **All-in-One** et une version **scindée en trois fichiers** pour faciliter l'importation.
5. **Dossier "Le Site à héberger"** : Inclut l'ensemble des fichiers du site. **Attention** : Ne copiez pas le dossier principal directement, mais bien l'ensemble des **dossiers qu'il contient** vers votre hébergeur.
6. **Fichier "Accès Click-up"** : Contient les informations pour accéder à Click-up avec un compte e-mail `contact@bdarcadia.fr`. Veuillez noter que vous ne pourrez pas accéder à l'e-mail du site web, car le mot de passe n'est pas partagé pour des raisons de sécurité. Vous aurez accès à l'espace Click-up en tant que membre, ce qui vous permettra de consulter les informations, mais pas de les modifier.

## 4. Connexion et Sécurité de la Base de Données
- Pour des raisons de sécurité, la version en ligne du site utilise un **nom d'utilisateur** et un **mot de passe** spécifiques pour accéder à la base de données. Toutefois, dans la version partagée, la connexion se fait sans mot de passe, avec l'utilisateur **root** par défaut, et l'adresse de connexion est `localhost`. Cela simplifie les tests.

## 5. Base de Données MongoDB en Ligne
- Pour des raisons de stratégie et de simplicité, une **base de données MongoDB** est utilisée et accessible en ligne jusqu'à la fin de l'évaluation.
- MongoDB est relativement lourd, mais les scripts que j'ai réalisés pour son utilisation sont assez légers. L'utilisation en ligne permet de faciliter les tests sans avoir à télécharger un gros fichier.

## 6. Configuration du Serveur SMTP
- Pour l'envoi de courriels (création d'utilisateurs par un administrateur, utilisation du formulaire de contact), le **serveur SMTP** de l'hébergeur est configuré et actif pour la version en ligne du site.
- En version locale, cette fonctionnalité ne fonctionnera pas sans configuration d'un serveur SMTP local et mise à jour des fichiers concernés (espace de contact et section administrateur).

## 7. Hébergement et Configuration du Site Web
- Lorsque vous copiez le site vers votre serveur, faites attention à la **configuration de la connectique** sur des environnements comme **XAMPP**. L'objectif n'est pas de fournir un tutoriel sur la création d'un serveur web, car cela dépasse le cadre de cet ECF. Cependant, il est important de **vérifier les chemins de connexion** pour éviter des erreurs.
- Il est recommandé d'**héberger directement le site sur une solution distante**. Cela simplifie la gestion des services tels que le **serveur SMTP** et le **serveur webmail**. Soyez attentif aux détails de configuration, notamment les chemins de fichiers, pour éviter des erreurs potentielles.
- Dans le dossier **"Le Site à héberger"**, vous trouverez une partie "**exemple XAMPP**" contenant la configuration que j'ai utilisée. Initialement, je prévoyais de créer un document détaillé sur la configuration de XAMPP, mais cela aurait dépassé le cadre de cet ECF, qui se concentre sur les compétences en développement.

Ces prérequis sont essentiels pour une bonne installation et configuration du site web. Ils garantissent que toutes les fonctionnalités, y compris la connexion à la base de données et l'envoi de courriels, fonctionneront correctement, que ce soit en local ou sur un hébergeur distant.
