# Kimanj

## But
Kimanj est une application de sondage ayant pour but de désengorger les conversations Messenger.

## Fonctionnalités (v2) :
* Connexion via le [site etu](https://etu.utt.fr/api/panel) de l'UTT
* Réinitialisation des réponses sur certaines questions via cron
* Réponses anonymes
* Résultats publics ou non
* Affichage ergonomique des questions
* Développé avec Symfony 4.4 / Doctrine 2.7

## Installation
###0) Prérequis
* Php 7.1
* MariaDB / MySQL 5.7
* composer
###1) Récupérez le repos et installez les dépendances
```
sudo -u www-data git clone https://github.com/larueli/kimanj.git
sudo -u www-data cd kimanj && composer install
sudo -u www-data cp kimanj/.env kimanj/.env.local
```
### 2) Configurez le projet et la BDD
Editez ensuite le ``.env.local`` à votre guise, notamment pour la [BDD](https://symfony.com/doc/4.4/doctrine.html#configuring-the-database)
### 3) Migrez la BDD
```
# Si besoin de créer la BDD
# sudo -u www-data php bin/console doctrine:database:create
sudo -u www-data php bin/console make:migration
sudo -u www-data php bin/console doctrine:migrations:migrate
```
* Configurez ensuite nginx comme indiqué [ici](https://symfony.com/doc/4.4/deployment.html).
* Ajoutez cette ligne dans votre cron : ``mn hour * * * sudo -u www-data php /var/www/kimanj/bin/console app:cleanup`` pour l'effacement automatique, et mettez la valeur ``HEURE_RAZ={hour}h{mn}`` dans le env.

# Remettre à zéro la BDD

## Première méthode (sans drop de la DB)
```
# Stopper le serveur web / bloquer l'accès puis

sudo -u www-data php bin/console doctrine:schema:drop --full-database

# Effacer les dossiers src/Migrations et Images puis

sudo -u www-data php bin/console make:migration
sudo -u www-data php bin/console doctrine:migrations:migrate
```
## Deuxième méthode (avec drop de la DB)
```
# Stopper le serveur web / bloquer l'accès puis
# DROP DB;
# Effacer les dossiers src/Migrations et Images puis

sudo -u www-data php bin/console doctrine:database:create
sudo -u www-data php bin/console make:migration
sudo -u www-data php bin/console doctrine:migrations:migrate
```

# Auteur

Je suis [Ivann LARUELLE](https://www.linkedin.com/in/ilaruelle/), étudiant-ingénieur en Réseaux et Télécommunications à l'[Université de Technologie de Troyes](https://www.utt.fr/), école publique d'ingénieurs.

N'hésitez pas à me contacter pour me signaler tout bug ou remarque. Je suis joignable à [ivann.laruelle@gmail.com](mailto:ivann.laruelle@gmail.com).
