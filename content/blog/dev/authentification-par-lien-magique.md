---
type:               "post"
title:              "Authentification par lien magique"
date:               "2018-03-14"
publishdate:        "2018-03-14"
draft:              false

description:        "Retour d'expérience sur la mise en place d'authentification par lien de connexion."

thumbnail:          "images/posts/thumbnails/magic-link.jpg"
header_img:         "images/posts/headers/magic-link.jpg"
tags:               ["Authentification", "Email", "Sécurité", "MagicLink"]
categories:         ["Dev"]

author:    "mcolin"
---

# Lien magique

Nous avons récemment pris le parti pour l'un de nos dernier projet de proposer une authentification par "lien magique". Voici ce qui nous a poussé à faire cette expérience ainsi que nos retours.

L'authentification par "lien magique" est notamment utilisée par Slack qui propose cette fonctionnalité dans son client et qui nous a grandement inspiré.

![](images/posts/2018/magic-link-slack.png)

Pour ce projet, avons utilisé cette méthode d'authentification sur deux app mobile ainsi que sur une app backend.

## Fonctionnement

L'authentification par lien magique déporte toute la gestion des mots de passe sur la boîte mail de l'utilisateur. Celui-ci fournit son adresse email à l'application lorsqu'il souhaite s'y connecter, un lien d'authentification contenant un token lui est envoyé. L'utilisateur se connecte ensuite à sa boite mail puis clique sur le lien et est ensuite connecté sur l'application.

![](images/posts/2018/magic-link.png)

## Un constat

Qu'est ce qui nous a poussé à mettre en place ce système ? C'est d'abord une idée qui a germé face à un constat.

En tant que développeurs et utilisateurs d'internet, nous sommes plusieurs à avoir fait le constat que beaucoup d'utilisateurs oublient très fréquemment leur mot de passe. En effet, une fois connecté à une application, grâce à des mécanismes comme le "Remember Me" ou les sessions longues, il peut se passer plusieurs semaines voir mois avant de devoir resaisir son mot de passe. Un grand nombre de connexions passent alors par une procédure d'oubli de mot de passe et donc par la boite email de l'utilisateur.

![You can't leak passwords if you don't store passwords.](images/posts/2018/you-cant-leak-passwords-meme.png)

Ensuite nous y avons vu quelques avantages :

* Sans mots de passe à gérer, nous n'aurions pas à développer les fonctionnalités de l'oubli et de changement de mots de passes, donc un gain de temps.
* Les mots de passe utilisateurs sont généralement faibles, parfois identiques à travers plusieurs services. Ça serait pas un mal niveau sécurité de s'en dispenser, surtout que les boîtes mail sont de plus en plus sécurisées (authentification double facteur sur gmail par exemple).
* La création de compte devient plus rapide et plus simple, pas besoin de trouver un mot de passe qui correspond aux règles de l'application ni de s'en souvenir.

Enfin, nous avouons que techniquement, nous trouvions cela intéressant et motivant à developper. Cela apportait un peu de fraîcheur à une fonctionnalité développée sur chacune de nos applications de la même façon.

## Développement et premiers retours

Le développement de cette fonctionnalité a été relativement rapide et effectivement sur le coup, cela nous a permis de mettre en place un système d'authentification très rapidement.

Ce temps gagné a été amplifié par le fait que, dans le cadre de ce projet, cette authentification serait utilisée sur 3 applications via une API. Donc on a gagné du temps en s'affranchissant de la gestion de mot de passe sur les 3 applications.

## A plus long terme

Au fur et à mesure de notre utilisation, nous avons fait plusieurs ajustements et perfectionnements qui font qu'à plus long terme, la mise en place de cette authentification par "lien magique" a été un peu plus coûteuse que l'authentification classique que nous utilisons d'habitude. Mais ce coût sera rapidement lissé si nous la mettons en place sur d'autres projets.

## Validation Apple

Une grosse interrogation qui est restée en suspens jusqu'à la fin est la question de la validation par Apple des applications.

Nos applications mobile ne permettent pas la création de compte et nous ne connaissons pas à l'avance les adresses emails qui seront utilisées par les testeurs Apple. Nous avons donc fait une première tentative en leur fournissant directement des liens de connexions sur des comptes utilisateurs que nous avons créés pour eux.

Après plusieurs soumissions avec une description de plus en plus précise de la démarche à suivre, nous nous sommes rendu compte les testeurs Apple avaient beaucoup de mal à sortir de leur procédure de test classique. En effet, les fiches de soumissions d'applications ne permettent que renseigner un *login* et un *mot de passe* en cas d'authentification requise.

A l'heure où j'écris ces mots, nous avons dû implémenter une authentification par mot de passe spécialement pour Apple. Mais nous ne désespérons pas de trouver un procédure de soumission qui leur convienne.

Si votre application permet la création de compte, cette méthode ne posera évidemment pas de soucis.

## Conclusion

Malgré quelques embûches, nous avons trouvé cette méthode d'authentification très intéressante tant niveau technique que côté expérience utilisateur. Il faut néanmoins bien faire attention à présenter cette méthode d'authentification de façon clair à l'utilisateur.

L'authentification par "lien magique" s'intègre particulièrement bien avec l'expérience utilisateur mobile où il est toujours difficile de taper des mots de passe et où l'authentification est généralement persistante.

Est-ce que cette méthodes d'authentification peut remplacer définitivement l'authentification classique par mot de passe ou doit elle seulement être proposé comme alternative ? L'avenir nous le dira :)

---

<div>Bearded man and mobile phone icons made by <a href="https://www.flaticon.com/authors/monkik" title="monkik">monkik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
