# Structure des fichiers

* core
  * ajax
    * ExtraTemplate.ajax.php : [Fichier de gestion des requêtes Ajax du plugin.](#ajax-query)
    
# Requêtes Ajax <a name="ajax-query"></a>
Afin de répondre aux requêtes Ajax, il est nécessaire de créer le fichier [ExtraTemplate.ajax.php](../../core/ajax/ExtraTemplate.ajax.php).

Dans un premier temps, il est nécessaire de vérifier que l'utilisateur est bien connecté pour répondre à la requêtes.

Le début du processus commence avec la commande __ajax::init()__. Une fois exécuté, il est possible de récupérer les paramètres avec la fonction __init('NOM_DU_PARAMETRE')__.

Une fois la requête traitée, il faut exécuter __ajax::success()__, sinon, une exception sera levée informant l'utilisateur qu'un problème a été rencontré.