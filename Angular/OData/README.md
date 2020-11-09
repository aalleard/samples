# OData

## query-params.ts

Fichier contenant une classe permettant de convertir des paramètres de requêtes :
* Filtres
* Tris
* Pagination
* ...
	
... en une URL permettant de requêter un backend répondant aux spcifications OData V4. 

Cette classe ne reprend pas l'intégralité de la spécification, mais est complétée au fur et à mesure que les besoins se présentent.

## sorter.ts

Fichier contenant une classe modelisant un critère de tri, et sa conversion en URL répondant aux normes OData.

Cette classe est utilisée dans la classe QueryOptions décrite précédemment.
