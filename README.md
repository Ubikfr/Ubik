Ubik
====

Je suis Ubik.  
Avant que l’univers soit, je suis.  
J’ai fait les soleils.  
J’ai fait les mondes.  
J’ai créé les êtres vivants et les lieux qu’ils habitent;  
Je les y ai transportés, je les y ai placés.  
Je suis. Je serai toujours...

Tout ça pour dire qu'aux vues de l'avancement du projet et de son caractère confidentiel, il y a très peu de chances que vous ayez quoique ce soit à faire ici. Merci de revenir quand ça ressemblera à quelque chose.


## Instalation
 * cloner ce dépot (duuh!)
 * le serveur doit pouvoir écrire dans certains dossiers: `chmod 777 cache public/content/blog`
 * installer [Composer](https://getcomposer.org/download/)
 * charger les dépendances grâce à `php composer.phar install` à la base du dépot.

## Configuration
 * éditer `config/default-sample.php` et le renommer en `config/default.php`
 * pour jouer avec la base de donnée, la struture de la table "Users" se trouve dans `doc/users.sql`
 * [Une brève documentation](https://github.com/Ubikfr/Ubik/blob/master/public/content/help.md).

## Configuration d'Apache
 * le DocumentRoot du serveur doit pointer vers le dossier `public`
 * par conséquent, le reste des fichiers se trouvent *sous* le DocumentRoot...

