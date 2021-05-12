# L33tBoards

## Description du projet

Projet Symfony LE4 - Réalisation d'une application web reprenant quelques principes de Reddit et de 9gag avec quelques améliorations supplémentaires

## Accès au drive de features

https://docs.google.com/document/d/17fSaiGtqa_60uI0C1pmnPi8ZPySKEmGfH2iAWPpEl_c/edit

## Import du projet

* Installer si ce n'est pas fait, `mysql` en version `5.7.x` ;
* Installer sur le poste de dévleoppement si ce n'est pas fait, `PHP` en version `7.4.x`
* Création et configuration du fichier `"/.env.local"` selon l'installation présente sur le poste de développement:
  * Utiliser le fichier "`.env`" pour créer le fichier "`.env.local`" avec le même contenu et modifier la variable `DATABASE_URL`
  * Modifier le contenu de la variable `DATABASE_URL` :
    * `DATABASE_URL="mysql://user:passwd@127.0.0.1:3306/l33tboards?serverVersion=5.7"`
    * `user` = nom d'utilisateur de connexion au serveur de base de données MYSQL (ex: `root` avec WAMP)
    * `passwd` = mot de passe de connexion au serveur de base de données MYSQL (peut-être vide selon les configs)
    * `127.0.0.1` = à laisser pour le dév. en localhost
    * `3306` = port du serveur laragon, wamp etc. (windows = `3306`)
* Se rendre dans un nouveau terminal dans le répertoire correspondant à la racine du projet (là où se trouve les dossiers & fichiers générés par symfony) ;
* Lancer l'installation sur le poste de développement des dépendances composer:
  * `composer install`
* Lancer le serveur (Laragon / WAMP / XAMP) pour le support PHP & MYSQL ;
* Créer la base de données si elle n'existe pas (phpmyadmin permettra de visualiser la base de données "l33tboards") :
  * `php bin/console doctrine:database:drop --force --if-exists`
  * `php bin/console doctrine:database:create`
* Lancer le serveur symfony avec la commande suivante :
  * `symfony server:start`
