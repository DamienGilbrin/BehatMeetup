***Atelier technique suite au Meetup ZendFr Behat/Browserstack***

Tu viens de participer au Meetup sur Behat/Browserstack, merci à toi d'être venu !

**La présentation**

- Behat : https://www.damiengilbrin.fr/behat
- BrowserStack : https://www.damiengilbrin.fr/browserstack


**Initialisation du projet**

Pour commencer, il te faut : 
- Docker d'installé sur ta machine
- Récupérer les sources de ce repo (git clone)
- Copier le fichier `.env.dist` en `.env`
- Lancer les container Docker via la commande `docker-compose up` (un peut long le premier lancement)
- Récupérer les vendor : 
  * Lance la commande `docker exec -it behatmeetupzendfr_php_1 bash` pour te connecter sur le container PHP
  * Execute cette commande pour installer les vendors : `composer install`

**Lancer le projet**

Une fois les container en cours d'execution, aller sur l'adresse :
`http://localhost:8080`

**Lancer les tests behat**

- Dans le container PHP (voir au dessus), lance les tests `./vendor/bin/behat` (tu peux ajouter ` --tags=NomDuTag` avec le nom du tag dans le @ dans le fichier de Feature)
- Pour les tests Selenium en local, il est possible de voir en temps reel l'execution du script à cette adresse : `http://127.0.0.1:6081` (Ne pas taper localhost mais bien l'ip de la boucle local !)

- Le point d'entrée : dans le dossier src/Controller/DefaultController.php
- Le service Cart : src/Service/CarServiceSession.php
- Les rendus Html/Twig : templates/default
- Les Scenarios : tests/Behaviour/Features
- Les Contexts : tests/Behaviour/Bootstrap/Context
- La config Behat : /behat.yml

**Pour tester avec Browserstack**

- Il faut créer un compter sur BrowserStack
- Récupérer son identifiant et sa clé et la saisir dans le fichier `behat.yml` au niveau des champs `username` et `access_key` (idéalement il faut utiliser le fichier `.env` mais je n'ai pas eu le temps)
- Avoir un compte Ngrok
- Installer Ngrok sur sa machine et lancer en écoutant le port http `8080`
- Inscrire l'url temporaire d'Ngrok dans le fichier `behat.yml` au niveau de `base_url`
- Lancer le test !

Il est possible d'utiliser `BrowserStack Local` à la place de Ngrok sauf que pour le moment je n'ai pas eu l'occasion de l'utiliser vu que j'avais déja un compte Ngrok.


**Pour me contacter**

Damien Gilbrin
contact@damiengilbrin.fr
https://www.damiengilbrin.fr
