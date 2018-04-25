# Sommaire

* [Structure des fichiers](#structure-des-fichiers)
* [Les objets Jeedom](#les-objets-jeedom)
  * [Les méthodes héritées de la classe eqLogic](#les-méthodes-héritées-de-la-classe-eqlogic)
    * [Les méthodes de classe](#les-méthodes-de-classe)
    * [Les méthodes de classe](#les-méthodes-statiques)
  * [Les commandes des objets](#les-commandes-des-objets)
* [Les requêtes Ajax](#les-requêtes-ajax)

# Structure des fichiers

* _core_
  * _ajax_
    * __ExtraTemplate.ajax.php__ : [Fichier de gestion des requêtes Ajax du plugin.](#les-requêtes-ajax)
  * _class_
    * __ExtraTemplate.class.php__ : [Fichier de gestion des objets](#les-objets-jeedom)
    * __ExtraTemplateCmd.class.php__ : [Fichier de gestion des commandes des objets](#les-commandes-des-objets)
    

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

> Ce fichier doit être inclut à partir du fichier [ExtraTemplate.class.php](../../core/class/ExtraTemplate.class.php).

# Les requêtes Ajax

Afin de répondre aux requêtes Ajax, il est nécessaire de créer le fichier [ExtraTemplate.ajax.php](../../core/ajax/ExtraTemplate.ajax.php).

Dans un premier temps, il est nécessaire de vérifier que l'utilisateur est bien connecté pour répondre à la requêtes.

Le début du processus commence avec la commande __ajax::init()__. Une fois exécuté, il est possible de récupérer les paramètres avec la fonction __init('NOM_DU_PARAMETRE')__.

Une fois la requête traitée, il faut exécuter __ajax::success()__, sinon, une exception sera levée informant l'utilisateur qu'un problème a été rencontré.