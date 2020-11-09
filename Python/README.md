## odata.py

Ce fichier contient un middleware permettant l'interpréatation d'une URL répondant aux normes OData pour l'utiliser avec le framework Django

L'impémentation des spécifications se fait au fil de l'eau en fonction des besoins rencontrés.

Les éléments déjà implémentés sont :
* Gestion des requêtes de décompte ($count)
* Gestion des tris ($orderby)
* Gestion des filtres ($filter)
* Gestion de la pagination ($skpi / $top)
* Gestion des relations ($expand)

## business_object.py

Dans le cadre du projet d'où sont extraits les fichiers, tout objet ayant un caractère "métier" héritera de la classe abstraite BusinessObject.

Cette classe permet une gestion centralisée des traitements communs à tous ces objets :
* Mapping des requêtes entrantes
* Complétion des données avant sauvegarde
* Contrôle des données avant sauvegarde
* Opérations de BD à réaliser avant/après l'accès en base
* Gestion des autorisations
* Gestion des statuts
* ...

## case.py

Implémentation d'un objet qui hérite des BusinessObject
