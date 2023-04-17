test-dev
========

Un stagiaire à créer le code contenu dans le fichier src/Controller/Home.php

Celui permet de récupérer des urls via un flux RSS ou un appel à l’API NewsApi. 
Celles ci sont filtrées (si contient une image) et dé doublonnées. 
Enfin, il faut récupérer une image sur chacune de ces pages.

Le lead dev n'est pas très satisfait du résultat, il va falloir améliorer le code.

Pratique : 
1. Revoir complètement la conception du code (découper le code afin de pouvoir ajouter de nouveaux flux simplement) 

Questions théoriques : 
1. Que mettriez-vous en place afin d'améliorer les temps de réponses du script


--> Utilisez un système de cache : Le système de cache peut aider à réduire le temps de réponse en stockant les résultats des requêtes précédentes pour les fournir plus rapidement lors des requêtes suivantes.

--> Utilisez des requêtes asynchrones : Les requêtes asynchrones peuvent aider à améliorer le temps de réponse en envoyant des requêtes simultanément au lieu de les envoyer une par une.

--> Optimisez l'infrastructure serveur : vous pouvez améliorer le temps de réponse en optimisant l'infrastructure serveur sous-jacente, en utilisant des serveurs plus puissants et en optimisant les réglages du serveur web.





2. Comment aborderiez-vous le fait de rendre scalable le script (plusieurs milliers de sources et images)

--> Utilisation d'une base de données efficace : une base de données bien conçue et optimisée peut également améliorer la performance de votre script. Utilisez des outils de profilage pour identifier les requêtes lentes et les optimiser. Vous pouvez également utiliser des outils de mise en cache tels que Redis ou Memcached pour stocker des données en mémoire vive et ainsi accélérer les accès.

--> Utilisation d'un système de files d'attente : pour traiter de grandes quantités de tâches en arrière-plan, il est recommandé d'utiliser un système de files d'attente comme RabbitMQ. Les tâches peuvent être mises en file d'attente et traitées par des travailleurs, ce qui permet d'éviter les problèmes de performance.


