# Dashboard

## dashboard.png

Rendu des composants présents dans ce répertoire.

Le grand cadre rouge correspond au composant du répertoire "dashboard", le petit à celui du répertoire "counter-tile"

## Réperoire dashboard

Ce répertoire contient le composant représentant la page dans son ensemble.

C'est d'ailleurs pour cela que le composant se nomme DashboardPage et non DashboardComponent. Page étant utilisé pour les conteneurs de haut niveau. Ils correspondent généralement à une route et une seule.

C'est lui qui est en charge de la mise en forme globale de la page, les sous composants utilisant la totalité de l'espace qu'il leur alloue.

Il est aussi en charge de collecter les données en appelant les différents web wervices, puis en les passant à ses enfants.

## Répertoire kpi-tile

Ce répertoire contient un composant dont l'utilité principale est de choisir le bon composant à appeler (un peu comme une fabrique).

En effet, les KPIs présentés sur le dashboard sont renvoyés du backend et peuvent être de types différents, sans que le front connaisse leur nombre ni leur ordre. Le dashboard appelle donc ce composant qui lui se charge d'appeler celui qui servira à représenter la KPI graphiquement.

Il aura pour autre utilité de centraliser la gestion des actions utilisateur. C'est lui qui sait quelle action effectuer lors d'un clic sur un KPI.

## Répertoire kpi-counter

Ce répertoire contient un composant servant à afficher un KPI de type compteur tout simple

Aucune intelligence n'y est présente, son rôle se limite à la mise en forme
