---
type:               "post"
title:              "Bonnes pratiques Symfony2 : notre condensé !"
date:               "2013-04-19"
lastModified:       ~

description:        "Bonnes pratiques Symfony2 : notre condensé !"

thumbnail:          "content/images/blog/thumbnails/kafeine.png"
banner:             "content/images/blog/headers/elephpant_elao_family.jpg"
tags:               ["Tips", "Symfony"]

authors:            ["tjarrand"]
---

## Après le Symfony Live 2013, pour tous ceux qui n'ont pas pu venir, voici notre condensé des bonnes pratiques à respecter avec Symfony2 :

 1. Lire et relire la **documentation officielle** pour rester à jour.

 2. Respecter les **codings standards** (voir <a title="Coding Standards" href="http://symfony.com/doc/master/contributing/code/standards.html" target="_blank">Coding standars</a> et <a title="PHP Coding Standards Fixer" href="http://cs.sensiolabs.org/" target="_blank">CSFixer</a>).

 3. Découpage en Bundle : Créer **un bundle** pour gérer **une fonctionnalité** globale (ex: partie forum) ou s'il a vocation à être **réutilisé**. *Attention : ne pas forcement découper ses fonctionnalités en plusieurs bundle si elle sont interdépendantes*.

 4. Définir une **configuration** pour ses bundles via le DI : permet de ne charger que le nécessaire, de valider la configuration, afficher des messages d'erreurs clairs, etc.

 5. Si possible, **ne pas mélanger Symfony2 et logique métier**. Découper les objets métier en deux : le mapping SF2 dans une entité, la logique métier dans un model distinct.

 6. **Pas de logique métier dans les contrôleurs**. Faire appel à des services et des repository puis passer le resultat à la vue.<br /> *Penser aux annotations pour alleger les controlleurs.*

 7. Toujours écrire des **FormType** pour faire des formulaires. Les déclarer en service et leur passer des arguments (plutôt que de leur passer des options).

 8. Faire **un service pour gérer la session** (qui prend la session en argument) plutôt que de la gérer à plusieurs endroits dans les contrôleurs.

 9. **Utiliser le DIC !** (<a title="The Dependency Injection Component" href="http://symfony.com/doc/current/components/dependency_injection/index.html" target="_blank">Dependency Injection Component</a>).

 10. **Travailler avec des Test** unitaires et fonctionnels. Avec les outils intégrés et ceux disponibles en parallèle.

 11. Utiliser **Twig** et **Assetic**.

 12. Écrire des **commandes** pour les tâches répétitives.

 13. **Internationaliser** dès le début du projet.

 14. En environnement de développement : Utiliser le **Profiler**, intercepter les redirections.

 15. En production : supprimer `app_dev.php` et autres, fournir une clé secrète, activer les CSRF, activer les caches Doctrine, personnaliser les pages d'erreurs.

### Et aussi :

De manière général, veiller à la qualité et respecter les bonnes pratiques dans tous les aspects du projet : configuration serveur, HTML, CSS, Javascript, Git, etc.

Un grand merci à <a href="https://connect.sensiolabs.com/profile/tucksaun" target="_blank">Tugdual Saunier</a>

### Sources:

Conférence au Symfony Live 2013 : <a href="https://speakerdeck.com/tucksaun/42-bonnes-pratique-pour-symfony2" target="_blank">"42 bonnes pratiques pour Symfony2"</a> par Tugdual Saunier - Sensio
