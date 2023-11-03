---
name: "Docker"
logo: "build/images/technos/docker.svg"
title:
    - "Docker, un système de conteneurisation"
    - "pour le développement d'applications"
articles: ['elao/rix-et-elao']
titleSeo: "Intégration de la solution Docker dans votre applicatif web - Elao"
metaDescription : "Elao a développé une expertise dans la mise en place de Docker, un système de conteneurisation pour le développement d'applications, pour ses clients. Nous pouvons vous accompagner grâce à notre expertise technique de Docker."
---

**Docker** est un système de **conteneurisation** qui permet de développer, déployer et exécuter des applications dans des conteneurs. Les conteneurs empaquettent le code de l'application et toutes ses dépendances, garantissant ainsi que l'application fonctionne de manière fiable et cohérente sur n'importe quel environnement de déploiement. Docker est devenu un élément essentiel de l'écosystème DevOps grâce à sa **simplicité d'utilisation**, sa **portabilité** et son **architecture orientée microservices**.

## Pourquoi utiliser Docker ?

- **Portabilité** : Avec Docker, une application et son environnement peuvent être empaquetés en un conteneur qui peut être exécuté sur n'importe quel système supportant Docker, éliminant ainsi le problème du "ça marche sur ma machine".
- **Consistance et isolation** : Chaque conteneur fonctionne de manière isolée, ce qui signifie que les applications sont moins susceptibles de subir des interférences et des conflits entre elles.
- **Déploiement rapide** : Les conteneurs peuvent être créés et déployés en quelques secondes, ce qui est significativement plus rapide que le déploiement d'applications sur des machines virtuelles traditionnelles.
- **Développement et intégration continus** : Docker s'intègre bien avec les pipelines CI/CD, permettant une automatisation facile du processus de développement et de déploiement.
- **Utilisation optimisée des ressources** : Les conteneurs Docker utilisent les ressources de manière plus efficace que les machines virtuelles, permettant une densité d'hébergement plus élevée et une utilisation optimale des ressources.
- **Écosystème riche** : Docker Hub propose un registre public de conteneurs qui peuvent être utilisés comme bases pour construire et déployer des applications.
- **Gestion simplifiée** : Docker simplifie la gestion des applications en décomposant les applications complexes en conteneurs plus petits et gérables.
- **Sécurité améliorée** : L'isolement des conteneurs limite les risques de sécurité et permet de contrôler plus finement l'accès aux ressources.

## Son utilisation chez Elao

Depuis quelques années déjà, nous conteneurisons nos environnements de développement grâce à une solution maison et opensource : [Manala](https://www.manala.io/).
Cet outil nous permet de décrire l'environnement de notre projet, via une configuration texte, puis de monter et lancer un ou plusieurs conteneurs pour nous permettre d'évoluer dans un **environnement parfaitement maitrisé et paramétrable** à l'envie, mais surtout représentatif de l'environnement de production.
En bonus, cela nous permet également d'utiliser ce même environnement / conteneurs pour la <abbr title="Continuous Integration">CI</abbr>
Et sans plus de suspense, la solution que nous privilégions actuellement pour construire les images de ces environnements n'est autre que **[Docker](https://www.docker.com/)** !

## En savoir plus

- [Les services proposés par Elao](./nos-services)
