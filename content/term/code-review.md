---
name: "Revue de code"
title:
    - "La revue de code (code review) avec"
    - "GitHub, PHPStan et PHPUnit"
articles: []
titleSeo: "La revue de code (code review) avec GitHub, PHPStan et PHPUnit, une étape qualité cruciale"
metaDescription : "Chez Elao, nous pratiquons de façon systématique la revue de code, appuyée par l'utilisation de PHPStan et PHPUnit, pour un site ou une application de qualité"
---

La **revue de code** (aka _code review_ pour les anglophones) est une étape **cruciale** dans notre [processus de développement](./nos-services/application-web-et-mobile). Elle consiste à examiner systématiquement le code source nouvellement développé avant qu'il ne soit intégré à la base de code principale. Cette pratique vise à assurer la **qualité**, la **sécurité** et la **conformité** du code avec nos standards de développement élevés.

Celle-ci est enrichie par des outils automatisés : nous intégrons **PHPStan pour le linting** et **PHPUnit pour les [tests unitaires et fonctionnels](./nos-services/application-web-et-mobile#tests)**, ce qui renforce considérablement la qualité et la fiabilité du code avant même la révision manuelle par nos développeurs.

## Processus de revue de code chez Elao :

- **Analyse et tests automatisés** : Avec chaque <abbr title="Proposition de code">pull request</abbr> sur GitHub, [PHPStan](https://phpstan.org/) analyse le code à la recherche d'erreurs potentielles, tandis que [PHPUnit](https://phpunit.de/) exécute une série de tests pour vérifier que chaque nouvelle fonctionnalité ou correction fonctionne comme prévu sans introduire de régressions.
- **Retour d'information instantané** : Ces outils fournissent des retours automatisés immédiats, soulignant les erreurs et les défaillances de tests, ce qui permet aux développeurs de les corriger en amont de la revue de code humaine.
- **Focus sur la révision de qualité** : En éliminant les erreurs élémentaires et en confirmant la fonctionnalité à travers des tests automatisés, nos réviseurs peuvent se concentrer sur l'optimisation du code et la revue de la logique métier plus complexe.
- **Amélioration continue** : Les résultats de PHPStan et PHPUnit sont intégrés dans nos pipelines <abbr title="Continuous Integration/Continuous Delivery">CI/CD</abbr>, garantissant que chaque aspect du code est constamment surveillé et amélioré.

## Avantages de l'utilisation de PHPStan et PHPUnit dans nos revues de code :

- **Détection avancée des erreurs** : PHPStan assure la robustesse du code (détecte des soucis potentiels de code pur, grâce aux types notamment, comme un TypeScript). Quant à PHPUnit, il assure que les modifications de code apportées (fonctionnalité, bug fix, ...) sont conformes à ce qui est attendu et qu'elles n'introduisent pas de régression.
- **Assurance qualité** : Les tests automatisés garantissent que les nouvelles fonctionnalités ne cassent pas les comportements existants, maintenant la stabilité de l'application.
- **Efficacité et productivité** : Les développeurs peuvent se concentrer sur des problèmes de haut niveau au lieu de bugs triviaux, optimisant ainsi le temps de développement et la productivité.
- **Culture de la qualité** : L'utilisation de ces outils soutient une culture d'excellence et de responsabilité, où la qualité est vérifiée à plusieurs niveaux avant la mise en production.

## En savoir plus :

- [Notre utilisation de GitHub chez Elao](./github.md)
- [Applications web et mobile: l'approche que nous proposons](./nos-services/application-web-et-mobile)
