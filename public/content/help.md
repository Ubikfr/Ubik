---
titre: Aide
template: default
access: public
content: markdown
js:
  - /assets/js/vendor/highlight-0.8.min.js
---
# Quelques explications

# Qu'est-ce qu'une page?

Une page est un fichier .md qui se trouve dans le dossier `/public/content/`

Dans ce fichier, il y a deux parties: méta-données de la page et contenu.

Les méta-données sont en YAML et ressemblent à ça:

```yaml
---
titre: Le titre
template: default
access: public
content: markdown
js:
  - /assets/js/un-script.js
  - /assets/js/un-autre-script.js
---
```
 * `titre` sera utilisé comme titre de la page
 * `template` indique que le template `/templates/default.tpl` sera utilisé pour rendre la page
 * `access` dit que la page est publique
 * `content` dit que la suite du fichier est écrite en Markdown

Seules ces dernières entrée sont obligatoires. On peut en ajouter d'autre (comme `js` dans l'exmple) qui rajouterons divers objets à la page (ici deux scripts).  
Pour le moment, les entrée suivantes sont gérées:

 * js qui charge du javascript
 * css qui charge du css supplémentaire
 * dao qui charge un objet lié à la DB.

L'entrée `content` gère `markdown` ou `html`

# Comment Ubik affiche une page?

## Initialisation:

Le répertoire racine du site est `/public`, dedans un `.htaccess` redirige toutes les requêtes vers le fichier `index.php`.  
A partir de là, après avoir récupèré le fichier de config et lancé l'autoload, on:

 * démarre une session php (classe dans `/Ubik/PhpSession.php`)
 * charge un container Pimple (classe dans `/Ubik/Utils/Container.php`) 
 * démarre une application Tonic


## Tonic s'occupe de la requête

[Tonic](http://www.peej.co.uk/tonic/) propose une API REST dont tous les fichiers se trouvent dans le répertoire `/Ubik/Ressources/`  
Pour prendre un exemple concrêt, la présente page situé à l'url `/help` trouve correpondance dans la resource _PageResource_ dans `/Ubik/Ressources/Page.php`.  
Ici la fonction `setup()` est apellée: on vérifie que le ficher `/public/content/help.md` éxiste bien, on charge les données, on vérifie les droits d'accès à la page...  
Si tout c'est bien passé, Tonic lance une seconde fonction (en l'occurence `displayPage()`) selon la méthode de la requête.  
Comme, ici, on fait une requête _GET_ standard, Tonic va donc choisir la fonction qui contient en annotation:
```
/**
 * @method GET
 */ 
```
Mais on peut faire des requêtes avec les verbes _POST_, _PUT_, _DELETE_ etc...  
Cette fonction va charger un objet (ici Dao_Page) qui se charger du rendu de la page à partir de toutes nos données puis répondre au client:
```php
return $response
```
suivi, dans `index.php`, de:
```php
$response->output();
```

# Documentation des dépendances:

 * [PHP Markdown Extra](http://michelf.ca/projects/php-markdown/extra/) pour la partie markdown
 * [Tonic](http://www.peej.co.uk/tonic/) API REST
 * [highlight.js](http://softwaremaniacs.org/soft/highlight/en/) pour faire du coloriage syntaxique
 * [Smarty](http://smarty.net/) pour les templates
 * [Flywheel](https://github.com/jamesmoss/flywheel) pour gérer les fichiers
 * [phpseclib](http://phpseclib.sourceforge.net/) pour crypter des trucs secrets
 * [Pimple](http://pimple.sensiolabs.org/) pour injecter des données
 * [Spyc](https://github.com/mustangostang/spyc/) pour lire et écrire du YAML
