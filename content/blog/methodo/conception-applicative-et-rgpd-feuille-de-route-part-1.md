---
title:              "Conception applicative et RGPD, feuille de route - partie 1."
date:               "2023-09-19"
lastModified:       ~
tableOfContent:     3

description:        "Cette première partie illustre nos réflexions afin d'intégrer le RGPD lors de la conception d'applicatifs web. Identification des données, définition des traitements et validation de leur licéité."

thumbnail:          content/images/blog/thumbnails/big-data-is-watching.jpg
credits:            { name: "ev", url: "https://unsplash.com/@ev" }
tags:               ["RGPD", "Conformité", "Privacy"]
authors:            ["gfaivre"]
tweetId:            ""
---

Dans cette suite d'articles j'essaierai de présenter **NOTRE compréhension** de certains points du Règlement Général sur la Protection des Données (RGPD), auxquels il est bon de porter attention lorsque nous concevons des applications.
Nous aborderons également les moyens que nous essayons de mettre en oeuvre de manière à pouvoir informer nos clients des responsabilités qu'ils ~~peuvent avoir~~ ont par rapport aux données personnelles de leurs utilisateurs.

!!! info "DISCLAIMER"
    En aucun cas cet article ne vaut conseil juridique, **nous ne sommes pas juristes**, simplement des professionnels qui essaient d'avoir une bonne lecture de la législation et de faire en sorte que les données personnelles que nous sommes amenés à manipuler pour le compte de nos clients le soit avec l'égard qui leur est dû.

## Introduction

Le [RGPD](https://www.cnil.fr/fr/reglement-europeen-protection-donnees) et les préoccupations liées au traitement des données personnelles, sont dorénavant présents dans beaucoup des aspects des métiers de l'IT (ou devraient l'être). Et bien qu'en tant que prestataire nous ne soyons pas directement concerné par le règlement, nous sommes au cœur de la problématique et avons quoiqu'il en soit un [**devoir de conseil**](https://www.cnil.fr/sites/default/files/atoms/files/rgpd-guide_sous-traitant-cnil.pdf) auprès de nos clients.

Ce devoir de conseil nous impose d'avoir un minimum de réflexion concernant les données que nos clients nous demandent de collecter, stocker et/ou traiter.

Convaincus qu'en tant que concepteurs d'applications nous avons un rôle à jouer dans la bonne application des textes et surtout dans la préservation des données personnelles, nous partageons notre démarche et le cheminement que nous suivons pour y parvenir.

Alors on est d'accord, le texte est vaste, complexe et bien malin le non juriste qui pourrait prétendre en avoir saisi toutes les subtilités.
De plus, veiller à la bonne conformité d'un applicatif prend du temps et donc coûte de l'argent. Toutefois, d'expérience nous considérons qu'une mise en conformité « a posteriori » coûtera encore plus cher à mettre en oeuvre.

Enfin, nous sommes partis du principe qu'il ne faut pas que ces difficultés de compréhension et/ou d'interprétation nous empêchent d'aborder et/ou traiter les points que nous pourrions identifier comme problématiques dans nos réalisations.

## Notions essentielles

### Qu'est ce qu'une donnée personnelle 🤔 ?

Les données personnelles sont bien évidemment le point central de la réflexion que tout professionnel doit mener lorsqu'on lui demande d'en manipuler. Mais la première difficulté est de bien comprendre ce qu'est une donnée personnelle au sens de la **réglementation Européenne**.

**SPOILER**: Ça n'est pas forcément aussi simple que l'on pourrait le penser.

Pour commencer, que dit le texte en lui-même concernant celles-ci ?

> Une donnée personnelle est toute information se rapportant à une personne physique identifiée ou identifiable.
<cite>[Article 4.1 du RGPD](https://eur-lex.europa.eu/legal-content/FR/TXT/HTML/?uri=CELEX:32016R0679#d1e1438-1-1)</cite>

Ça n'a l'air de rien mais ça ouvre en fait pas mal de portes !
On comprendra donc qu'une personne peut-être identifiée de **manière directe** comme par exemple son nom, ou **indirecte** et là on rentre dans le plus subjectif puisqu'une donnée indirecte peut prendre plusieurs formes comme l'explique très bien la [CNIL](https://www.cnil.fr/fr/rgpd-de-quoi-parle-t-on):

**On y trouve en vrac:**

- un identifiant unique (numéro de SS ou numéro client);
- un numéro de téléphone;
- une donnée biométrique;
- et au delà de tout ça, toute information qui peut **constituer l'identité d'un individu** (voix, image, physique, données physiologiques...).

On retiendra également que l'identification d'une personne physique peut se faire par le biais **d'une seule donnée** comme par un **croisement de plusieurs**.

!!! info ""
    Pour en avoir le coeur net vous pouvez aller vérifier directement le texte, ça se passe à [l'article 4 (1) des dispositions générales](https://eur-lex.europa.eu/legal-content/FR/TXT/HTML/?uri=CELEX:32016R0679#d1e1438-1-1).

À noter que la littérature traitant le sujet utilise souvent les acronymes **DCP** (Données à Caractère Personnel) ou **PII** (Personally Identifiable Information) en anglais.

### C'est quoi « traiter » une donnée personnelle ?

Maintenant que notre vision de là où l'on met les pieds est un poil plus précise, les prochaines questions à se poser sont:

- Est-ce que je vais être amené à manipuler de la donnée personnelle ?
- Est-ce que je vais devoir effectuer un traitement sur cette donnée ?

En ce qui concerne la première question si l'application que vous développez ne manipule pas du tout de données personnelles (🧐) vous pouvez arrêter votre lecture ici.

Dans le cas contraire il faut dorénavant se poser la question de ce qu'est un **traitement**.

Et tout comme la définition de la donnée personnelle, cette notion de traitement est relativement large.

> _Toute opération ou tout ensemble d'opérations effectuées ou non à l'aide de procédés automatisés et appliquées à des données ou des ensembles de données à caractère personnel, telles que la collecte, l'enregistrement, l'organisation, la structuration, la conservation, l'adaptation ou la modification, l'extraction, la consultation, l'utilisation, la communication par transmission, la diffusion ou toute autre forme de mise à disposition, le rapprochement ou l'interconnexion, la limitation, l'effacement ou la destruction._
<cite>[Article 4.2 du RGPD](https://eur-lex.europa.eu/legal-content/FR/TXT/HTML/?uri=CELEX:32016R0679#d1e1438-1-1)</cite>

Voilà, autant vous dire que si vous manipulez de la données personnelle dans vos applications, vous faites forcément du traitement quelque part !

!!! info "Quid des données « génériques » ?"
    La question revient souvent et laisse pas mal de monde dubitatif.
    Si l'on se réfère à la définition de la donnée personnelle il faut qu'une donnée puisse **identifier une personne**.
    Les données comme un courriel contact@elao.com ou encore un numéro de standard **ne sont donc pas des données personnelles au sens du RGPD**.

!!! info "Du support de stockage des données"
    Point important qui tient à deux mots dans l'extrait de texte ci-dessus: *«... d'opérations effectuées **ou non** à l'aide de procédés automatisés ...»* ce qui signifie que l'on ne **considère pas uniquement des procédures numériques** pour caractériser un traitement de données. Un carnet papier, une photocopie, une note manuscrite contenant des DCP **SONT** des traitements de données personnelles ! Celles-ci doivent donc être protégées **selon les mêmes conditions qu'un fichier numérique**.

### Mon client est-il soumis à la réglementation ?

Posons nous à présent la question de savoir si le projet sur lequel on va être amené à travailler sera soumis à la législation (En vrai sauf cas de figure vraiment spécifique, il y a de fortes chances que oui).

Il faut déjà oublier l'idée que seules les entreprises Européennes y sont soumises, c'est bien plus large que ça. En effet tout organisation (publique comme privée) peut-y être soumise dès lors que:

- Son siège social est établi dans l'Union Européenne;
- Qu'elle traite des données de citoyens européens.

Inutile d'aller chercher plus loin ou de vous poser la question **du lieu de traitement des données**, il n'a en soit que très peu d'importance !

Et d'ailleurs, si vous avez des DCP qui quittent le territoire de l'UE, il y a de fortes chances que vous soyez déjà par cet aspect, dans l'illégalité.

Enfin, le RGPD concerne également les sous-traitants qui peuvent être amenés à traiter des données personnelles pour le compte de tiers et ça tombe bien, aux yeux du RGPD, nous sommes le sous-traitant de notre client final.

### Je ne suis pas sûr de correctement interpréter le texte !

Bienvenu au club !

Plus sérieusement, il n'y a guère que **l'épreuve du feu** qui permette d'éprouver une lecture correcte d'un texte de loi aussi vaste que celui-ci.

Ainsi la meilleure façon de valider notre compréhension reste de se référer:

- aux décisions rendues par la CNIL;
- aux décisions rendues par ses homologues européennes;
- et/ou directement par la CJUE (Cour de Justice de l'Union Européene).

Il est vrai qu'en pratique et à défaut d'avoir un conseil spécialisé on se réfère souvent à la [jurisprudence](https://gdprhub.eu/index.php?title=Advanced_Search) pour se faire une idée de comment interpréter un texte.

## Tenir compte des contraintes liées à la protection des données

### Le démarage du projet

Ça y est notre client nous fait confiance, il nous a retenus pour concevoir son application ET indirectement il nous fait confiance pour l'accompagner au mieux sur d'autres tâches, peut-être le choix d'un hébergeur, la conception de son infra mais et surtout (même si lui même ne le sait pas), sur la manière dont les données de ses utilisateurs seront traitées.

Parce que finalement, c'est un peu la finalité d'un applicatif, les donneés.

C'est bien évidemment **notre client qui aura le dernier mot** sur ce qu'il souhaite faire par rapport aux conseils que nous lui apporterons, mais nous aurons **fait notre travail** en lui fournissant les informations dont il a besoin pour prendre ses décisions.

Encore une fois, ce qui va suivre **n'est que le reflet de notre réflexion** afin d'améliorer la façon dont est abordé le sujet de la protection des données personnelles tout au long de la conception d'une application.

Le contenu n'est pas exhaustif et sera sans doute,, amené à évoluer dans le temps.

### Demandez à votre client s'il dispose d'un DPO

La première étape, même si elle semble évidente, est sans doute de demander à notre client s'il dispose d'un DPO, si c'est le cas il sera utile de le convier aux ateliers fonctionnels afin qu'il puisse prendre connaissance des différentes collectes de données envisagées et de la manière dont elles seront faites.
À défaut de lui soumettre une liste exhaustive de ces même données.

**N'hésitez pas à poser la question !**

 Il n'est pas rare que le DPO existe mais qu'il ne soit réduit qu'à un rôle de faire valoir « au cas où » il y ait un problème. Or son rôle va bien au delà puisqu'il ~~peut~~ doit être partie prenante de la conformité de son entreprise.

!!! info "C'est quoi un DPO et quel est son role ?"
    Le DPO (Data Protection Officer) ou Délégué à la protection des données a la charge de s'assurer de la conformité de son organisme en matière de protection des données.
    Bien qu'il ne soit pas obligatoire d'en disposer [sauf cas spécifiques](https://www.cnil.fr/fr/cnil-direct/question/reglement-europeen-le-delegue-la-protection-des-donnees-cest-obligatoire), sa désignation est **fortement encouragée par la CNIL**.

### Intégrez la gestion des données dès la phase de conception

Vous souhaitez sensibiliser et éduquer votre client et ses équipes ?
Amenez le sujet dès les phases de conception.

Les ateliers fonctionnels sont un excellent point de départ pour prendre connaissance de ce qui va être manipulé et de la sensibilité des données.
C'est une information importante à la fois pour le concepteur, pour l'infogérant, mais également pour les équipes métiers.

Dès lors que vous démarrez un atelier, rappelez aux différents intervenants qu'à partir du moment où une brique fonctionnelle va nécessiter de la donnée personnelle, il faudra se poser plusieurs questions sur la façon dont celle-ci va être gérée (collectée, conservée, traitée) et bien évidemment si l'on est en capacité de **justifier sa collecte**.

**Les questions à se poser avant de décider d'une collecte de données:**

- Quelle est la **finalité** du traitement (l'objectif de la collecte) ? Elle doit être **déterminée** et **explicite** !
- Veille-t-on à la **minimisation** des données (Quelles données sont réellements indispensables à la réalisation de l'objectif ) ?
- Ai-je la **licéité** du traitement (Peut-on légalement collecter ces données) ? Le RGPD prévoit **6 conditions** de licéité ([consulter cette liste](https://eur-lex.europa.eu/legal-content/FR/TXT/?uri=CELEX%3A32016R0679#d1e1937-1-1))
- Est-ce **pertinent** ?
- Doit-on demander **l'accord des utilisateurs** avant de réaliser cette collecte ?

N'hésitez pas également à rappeler que le « privacy by design » **est imposée** par [l'article 25](https://eur-lex.europa.eu/legal-content/FR/TXT/HTML/?uri=CELEX:32016R0679&qid=1684314015871#d1e3109-1-1) du RGPD.


### Identifiez les données concernées

À partir du moment où vous développez une application, qu'elle soit publique ou destinée à de l'intranet, il y a fort à parier que les données personnelles seront de la partie. Ne serait-ce que pour la partie authentification de vos utilisateurs.

Il faudra toutefois ne pas oublier que les données personnelles peuvent être **indirectes** et c'est plus sur ce type de données que l'effort sera nécessaire pour les identifier.

⚠️ Il n'y a pas besoin qu'une donnée soit nominative pour qu'elle soit une DCP (Un identifiant client, une plaque d'immatriculation, un NIR...) !
### Définir la base légale (licéité) du traitement

C'est probablement la phase la plus complexe, car la licéité d'un traitement ne s'effectue pas uniquement au regard des dispositions « Informatique et liberté » et RGPD mais également au regard de sa conformité au droit en général.

Afin de définir si un traitement de données est licite, il devra remplir l'une des 6 conditions suivantes:

- La personne ciblée par la collecte a consenti au traitement de ses données (Attention le consentement doit être receuilli pour **chacune** des finalités de traitement et il **ne peut y avoir de caractère lié** entre les finalités), il doit être **libre**, **spécifique**, **éclairée** et **univoque**;
- Le traitement est nécessaire à l'exécution du contrat;
- Le traitement est nécessaire au respect d'une contrainte légale à laquelle est soumise le responsable;
- Le traitement est nécessaire à la sauvegardes des intérêts vitaux (soit de la personne concernée soit d'une autre personne physique);
- Le traitement est nécessaire à l'exécution d'une mission d'intérêt public ou relevant de l'exercice de l'autorité publique;
- Le traitement est nécessaire aux fins des intérêts légitimes.

### Méfiez-vous de l'intérêt légitime

Derrière cette expression se cache l'une des bases légales prévues par le RGPD sur laquelle **peut** se fonder un traitement de données personnelles.

C'est paradoxalement celui qui est le plus difficile à justifier et celui derrière lequel se réfugient nombre d'organisation (L'apparente facilité à évoquer l'intérêt légitime est souvent un piège).

Il est soumis à [3 conditions](https://www.cnil.fr/fr/les-bases-legales/interet-legitime):

- L'intérêt poursuivi doit être légitime;
- Il ne peut être retenu que si le traitement satisfait la condition de nécessité;
- Le traitement ne doit pas heurter les droits et intérêts des personnes dont les données sont traitées.

## Conclusion de cette première partie

Si vous avez survécu à cette première partie (félicitations !), vous avez sans doute plus de questions en tête que vous n'en aviez avant cette lecture, rassurez-vous c'est normal.

Nous ne saurons être exhaustifs sur le sujet tant le texte est vaste. Toutefois s'il est une chose à retenir, c'est que ce sujet doit être abordé **au plus tôt** au cours de la phase de conception.

En effet, bien que cela ne remette pas en cause l'applicatif et la réussite du projet, la mise en conformité **a posteriori** peut vite devenir un véritable casse-tête.

Dans notre prochaine article nous aborderons la phase de **réalisation** où nous mettrons en avant les bonnes pratiques à retenir et à mettre en oeuvre tout au long de la durée de vie du projet.

## Sources

- [Le règlement général sur la protection des données - RGPD (CNIL)](https://www.cnil.fr/fr/reglement-europeen-protection-donnees)
- [Règlement (UE) 2016/679 du Parlement Européen et du Conseil](https://eur-lex.europa.eu/legal-content/FR/TXT/?uri=CELEX%3A32016R0679)
- [Le RGPD en 10 minutes (ou un peu plus)](https://blog.imirhil.fr/2022/11/19/rgpd-en-10-minutes-1.html)
