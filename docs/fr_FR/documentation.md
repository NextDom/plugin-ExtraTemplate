# Sommaire

* [Structure des fichiers](#structure-des-fichiers)
* [La page principale du plugin](#la-page-principale-du-plugin)
* [Les objets Jeedom](#les-objets-jeedom)
  * [Les méthodes héritées de la classe eqLogic](#les-méthodes-héritées-de-la-classe-eqlogic)
    * [Les méthodes de classe](#les-méthodes-de-classe)
    * [Les méthodes de statiques](#les-méthodes-statiques)
  * [Les commandes des objets](#les-commandes-des-objets)
* [Les requêtes Ajax](#les-requêtes-ajax)
* [Les traductions](#les-traductions)
  * [Dans du code PHP](#dans-du-code-php)
  * [Dans du code HTML](#dans-du-code-html)

# Structure des fichiers

* _core_
  * _ajax_
    * __ExtraTemplate.ajax.php__ : [Fichier de gestion des requêtes Ajax du plugin.](#les-requêtes-ajax)
  * _class_
    * __ExtraTemplate.class.php__ : [Fichier de gestion des objets](#les-objets-jeedom)
    * __ExtraTemplateCmd.class.php__ : [Fichier de gestion des commandes des objets](#les-commandes-des-objets)
  * _i18n_ : [Répertoire contenant les traductions du plugin](#les-traductions)
* _desktop_ : [Répertoire contenant les fichiers liés à la page principale](#la-page-principale-du-plugin)
  * _js_
    * __ExtraTemplate.js__ : [Fichier Javascript de la page principale](#gestion-des-commandes-de-vos-objets)
  * _css_
    * __ExtraTemplate.css__ : [Fichier CSS de la page principale](#ajout-du-css)
  * _php_ : 
    * __ExtraTemplate.php__ : [Page principale du plugin](#la-page-principale-du-plugin)
    
# La page principale du plugin

La page principale du plugin est contenu dans le fichier [ExtraTemplate.php](../../desktop/php/ExtraTemplate.php).

C'est à partir de celle-ci que les principales actions devront être réalisées.

Elle commence généralement par une vérification de l'authentification de l'utilisateur : 

```php
if (!isConnect('admin')) {
    throw new \Exception('{{401 - Accès non autorisé}}');
}
```

> Il n'est pas nécessaire dans ce fichier d'inclure le core de Jeedom

Une fois cette vérification effectuée, le contenu de la page peut être écrit.

Les fichiers Javascript nécessaire au bon fonctionnement sont ajouté à la fin du document : 

```php
// Charge le fichier Javascript du plugin
include_file('desktop', 'ExtraTemplate', 'js', 'ExtraTemplate');
// Charge le fichier général des plugins de Jeedom
include_file('core', 'plugin.template', 'js');
```

## Contenu

La page est généralement divisée en 3 parties : 

* Une section contenant les actions de gestion (Création, configuration, etc.),
* Une section contenant la liste des objets du plugin,
* Un menu latéral permettant : 
  * D'ajouter un objet
  * De faire une recherche
  * D'afficher la liste des objets

## Gestion des commandes de vos objets

L'affichage et l'ajout des commandes dans l'interface sont réalisées par le fichier [ExtraTemplate.js](../../desktop/js/ExtraTemplate.js).

La gestion des commandes se fait par la fonction __addCmdToTable__

### addCmdToTable

Cette fonction possède un paramète ___cmd__ par lequel sont transmis les informations des commandes créées par défaut envoyées par Jeedom.

```javascript
function addCmdToTable(_cmd)
```

Il est cependant possible d'appeler directement cette fonction, cette pourquoi il est indispensable de vérifier et corriger la conformité de celle-ci : 
```javascript
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }
```

L'étape suivant consiste à ajouter la ligne au tableau avec les contraintes suivantes : 
* La ligne (`<tr>`) : 
  * Classe __cmd__,
  * Attribut __data-cmd_id__ avec pour valeur l'identifiant de la commande,
* Les colonnes (`<td>`) : 
  * Les balises contenant des données doivent posséder l'attribut __data-l1key__ avec pour valeur l'identifiant de la donnée et la classe __cmdAttr__,
  * Communément, la première cellule contiendra une balise `<span>` cachée à laquelle on attribut l'identifiant de la commande,
  * Chaque `<input>` doit posséder la classe Bootstrap __form-control__,
  * Une cellule doit indiquer le type de commande. L'ajout des listes de choix se fera par le Javascript de Jeedom : 
  ```javascript
    var typeCell  '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>' +
                  '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
  ```
  * Si la commande est numérique, il est possible d'ajouter un bouton de configuration et de test : 
  ```javascript
    if (is_numeric(_cmd.id)) {
        var commandCell = '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ' +
                          '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
  ```
  * Pour offrir la possibilité de supprimer une commande, il faut ajouter une balise avec la classe __cmdAction__ et un attribut __data-action__ avec la valeur __remove__.

Exemple : 
```javascript
    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">' +
             '<td>' +
               '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>' +
               '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">' +
             '</td>' +
             '<td>' +
               '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>' +
               '<span class="subType" subType="' + init(_cmd.subType) + '"></span>' +
             '</td>' +
             '<td>';
    if (is_numeric(_cmd.id)) {
        tr +=  '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ' +
               '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr +=       '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>' + 
              '</td>';
            '</tr>';
```

Il faut maintenant l'ajouter à la page puis l'initialiser : 

```javascript
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
```

## Ajout du CSS

Pour ajouter un fichier CSS, il faudra ajouter la ligne suivante au plugin : 

```
```

# Les objets Jeedom

La création, modification, sauvegarde et suppression des objets se font communément dans le fichier [ExtraTemplate.class.php](../../core/class/ExtraTemplate.class.php).

Il hérite de la classe eqLogic qui est la classe mère de tous les objets.

> Si vous vous utiliser les commandes, il est nécessaire d'inclure le fichier [ExtraTemplateCmd.ajax.php](../../core/ajax/ExtraTemplateCmd.ajax.php) au début du code.

## Les méthodes héritées de la classe eqLogic

### Les méthodes de classe

#### preInsert
```
public function preInsert()
```

#### postInsert
```
public function postInsert()
```

#### preSave
```
public function preSave()
```

#### postSave
```
public function postSave()
```

#### preUpdate
```
public function preUpdate()
```

#### postUpdate
```
public function postUpdate()
```

#### preRemove
```
public function preRemove()
```

#### postRemove
```
public function postRemove()
```

#### toHtml
```
public function toHtml($_version = 'dashboard')
```
Permet de modifier l'affichage du widget.

### Les méthodes statiques

#### cron
```
public static function cron()
```
Méthode appelée toutes les minutes par Jeedom.

#### cron5
```
public static function cron5()
```
Méthode appelée toutes les 5 minutes par Jeedom.

#### cron15
```
public static function cron15()
```
Méthode appelée toutes les 15 minutes par Jeedom.

#### cron30
```
public static function cron30()
```
Méthode appelée toutes les 30 minutes par Jeedom.

#### cronHourly
```
public static function cronHourly()
```
Méthode appelée toutes les heures par Jeedom.

#### cronDaily
```
public static function cronDaily()
```
Méthode appelée tous les jours par Jeedom.

## Les commandes des objets

Les commandes des objets sont traitées par le fichier [ExtraTemplateCmd.class.php](../../core/class/ExtraTemplateCmd.class.php).

> Ce fichier doit être inclut à partir du fichier [ExtraTemplate.class.php](../../core/class/ExtraTemplate.class.php).

# Les requêtes Ajax

Afin de répondre aux requêtes Ajax, il est nécessaire de créer le fichier [ExtraTemplate.ajax.php](../../core/ajax/ExtraTemplate.ajax.php).

Dans un premier temps, il est nécessaire de vérifier que l'utilisateur est bien connecté pour répondre à la requêtes.

Le début du processus commence avec la commande __ajax::init()__. Une fois exécuté, il est possible de récupérer les paramètres avec la fonction __init('NOM_DU_PARAMETRE')__.

Une fois la requête traitée, il faut exécuter __ajax::success()__, sinon, une exception sera levée informant l'utilisateur qu'un problème a été rencontré.

# Les traductions

Les chaines de caractères du plugin doivent se trouver dans le répertoire [i18n](../../core/i18n).

Chaque langue doit posséder un fichier au format Json avec pour nom le code de la langue en miniscule, _, puis le code du pays (Exemple fr_FR, en_US, es_ES, etc.).

Le format est le suivant : 
```
{
  "plugins\/ExtraTemplate\/desktop\/php\/ExtraTemplate.php": {
    "Action": "Action",
    "Activate": "Activer",
    "Add": "Ajouter"
  },
  "plugins\/ExtraTemplate\/plugin_info\/configuration.php": {
    "Global param 1": "Premier paramètre global"
  }
```

Le chemin du fichier à traduire doit commencer à partir de la racine de Jeedom et les / doit être échappés.
Ensuite, chaque ligne correspond à la clé que l'on trouve dans le document, puis la traduction qui sera affichée.

Pour utiliser les traductions dans les fichiers, il y a 2 cas.

> Attention, si vous utilisez l'inclusion de fichiers, le fichier à indiquer est celui qui inclut le fichier.

## Dans du code PHP

Il faut utiliser la fonction __ :
```
__('Chaine à traduire', __FILE__);
```

## Dans du code HTML

Il faut "entourer" la chaine de caractères avec 2 accolades : 
```
<button>{{Cliquez ici}}</button>
``` 

