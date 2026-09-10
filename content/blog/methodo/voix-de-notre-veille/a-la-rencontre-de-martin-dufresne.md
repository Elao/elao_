---
type:               interview
title:              "« On est toujours dans une roue » : à la rencontre de Martin Dufresne"
date:               '2026-10-05'
lastModified:       ~
description:        "Pourquoi l'IA nous fatigue-t-elle plus qu'elle ne nous soulage ? Où est passé le plaisir de développer ? Comment apprendre quand la machine a déjà la réponse ? Autant de sujets que nous avons abordés avec Martin Dufresne, développeur, chercheur indépendant et formateur, pour le deuxième épisode des voix de notre veille."
authors:            [msteinhausser, equentin]
tableOfContent:     3
tags:               [veille, ia]
thumbnail:          content/images/blog/2026/methodo/voix-de-notre-veille/a-la-rencontre-de-martin-dufresne/thumbnail.jpg
thumbnailResizeOptions: # l'image porte une bannière : on la complète au fond plutôt que de la rogner
    article_thumbnail.lg:
        fit: fill
        bg: white
    article_thumbnail.md:
        fit: fill
        bg: 1e7695

readingModes:
    intro: |
        « Les voix de notre veille », c'est notre façon d'aller à la rencontre de celles et ceux que nous lisons dans
        notre revue de presse. Après Emmanuelle Aboaf, nous avons interrogé Martin Dufresne le 4 août 2026, pendant
        près d'une heure vingt.

        Cet article existe en deux modes de lecture. Le **récit** en restitue l'essentiel, en un format narratif.
        L'**entretien intégral** rend l'échange tel qu'il s'est tenu, dans les mots de chacun.
    narratif:  { readingTime: 20 }
    entretien: { readingTime: 35 }

# Bibliographie plutôt que notes appelées : les articles de Martin sont déjà liés
# dans le corps, cette liste les rassemble en fin d'article, pour les deux modes.
footnotes:
    -   title: "Les articles de Martin dont nous avons parlé"
        key:   "articles"
        notes:
            -   text:   "« La Résilience par la Perte : pourquoi mon code doit mourir pour survivre »"
                url:    "https://martindufresne.com/blog/la-r%C3%A9silience-par-la-erte_pourquoi-mon-code-doit-mourir-pour-survivre/"
                source: "martindufresne.com"
            -   text:   "« La disparition des temps morts : l'autre visage de la fatigue liée à l'IA »"
                url:    "https://martindufresne.substack.com/p/la-disparition-des-temps-morts-lautre"
                source: "Substack"
            -   text:   "« Pourquoi les agents IA vont saturer vos semaines (et non les alléger) »"
                url:    "https://martindufresne.substack.com/p/pourquoi-les-agents-ia-vont-saturer"
                source: "Substack"
            -   text:   "« Le coût caché du codage de l'IA »"
                url:    "https://martindufresne.substack.com/p/le-cout-cache-du-codage-de-lia"
                source: "Substack"
            -   text:   "« La fin du code : que reste-t-il quand le logiciel ne vaut plus rien ? »"
                url:    "https://martindufresne.substack.com/p/la-fin-du-code-que-reste-t-il-quand"
                source: "Substack"
            -   text:   "« Plus de gens devraient écrire »"
                url:    "https://martindufresne.substack.com/p/plus-de-gens-devraient-ecrire"
                source: "Substack"
            -   text:   "« Soixante-dix pour cent »"
                url:    "https://martindufresne.substack.com/p/soixante-dix-pour-cent"
                source: "Substack"
            -   text:   "« L'idée de devoir réfléchir intensément me manque »"
                url:    "https://martindufresne.substack.com/p/lidee-de-devoir-reflechir-intensement"
                source: "Substack"
---

<!-- mode: narratif -->

Ce mois-ci nous sommes allés à la rencontre de [Martin Dufresne](https://martindufresne.com/), une trentaine d'années
dans l'informatique au compteur et une newsletter technique qu'il publie chaque semaine. Ayant commencé comme
développeur de jeux vidéo pour enfants, pour poursuivre avec du développement web, il travaille à présent depuis plus de
10 ans avec l'intelligence artificielle. Il mène également de la recherche indépendante et des travaux sur
l'informatique quantique depuis environ huit ans. Formateur en entreprise comme en école, il s'intéresse aussi à la
cybersécurité et à l'architecture logicielle. La vulgarisation est venue avec l'enseignement et en écrivant, notamment
sur l'un des sujets du moment : _« parce que l'IA, c'est bien le fun, mais on a rarement l'occasion de comprendre
comment ça fonctionne »_.

Un collègue nous l'a fait découvrir il y a près de deux ans, via sa newsletter. Ce qui nous accroche : des sujets
techniques, portés par des réflexions ancrées sur ses propres travaux, et un vrai talent pour les analogies parlantes.
On le lit [sur son blog](https://martindufresne.com/blog/) comme sur
[sa newsletter Substack](https://substack.com/@martindufresne).

Son énergie va aujourd'hui à un projet de recherche personnel appliqué à la cybersécurité : la morphogénèse logicielle,
un petit système d'exploitation qui s'auto-répare. _« Et ça fonctionne merveilleusement bien. ☺️ »_ (_on en reparle plus
loin…_ 👀)

Voici les grands sujets que nous avons abordés avec lui, et ce que nous en avons retenu.

## « On est toujours dans une roue »

Nous voulions aborder la fatigue, l'un des sujets qui nous a rapprochés de lui. Dans
[_« La disparition des temps morts : l'autre visage de la fatigue liée à l'IA »_](https://martindufresne.substack.com/p/la-disparition-des-temps-morts-lautre),
il décrit ce que produit la délégation à des agents IA des tâches faciles ou à faible valeur : il ne reste que des
tâches à forte charge de réflexion, sans ces petits moments qui laissaient le cerveau souffler. Chez Elao, la même
fatigue est apparue à mesure que ces outils se sont installés dans le quotidien, et ce sont les développeur·euses qui
ont tiré la sonnette d'alarme. Une journée de team building y a été consacrée, d'où deux pistes : des moments de
respiration hors de notre cœur de métier, et un rythme inspiré de **Shape Up**, trois semaines de production puis une de
relâche. Nous nous cherchons encore.

Eva lui a demandé quels garde-fous tiennent, chez lui, dans la durée. Son organisation d'abord : le travail de fond le
matin, où il se sait le plus performant, du sport l'après-midi pour se changer les idées, l'enseignement le soir. Ce qui
lui donne _« à peu près des demi-journées de travail, mais du travail très intensif »_. Puis le constat : _« tout le
monde pensait que ça allait dégager du temps. Finalement, ça en prend plus. »_ Comme l'IA fait une grosse partie du
travail, on prend deux à trois fois plus de tâches en se disant qu'elle s'en chargera, et _« on se retrouve à la fin de
la semaine bien plus fatigué qu'on ne l'était avant »_.

Sur notre approche, il nous encourage :

> Votre idée de réfléchir à une nouvelle approche de travail, ça va être gagnant pour tous.

Là où il voit la perte la plus nette, c'est sur la créativité :

> On est comme sur le mode pilote automatique, tout le temps, à toujours générer du travail, sans vraiment réfléchir. On
> n'arrête pas de produire, de produire, de produire et finalement, tout se ressemble. Il n'y a pas vraiment de
> renouvellement, parce qu'on n'a plus le temps de renouveler nos propres idées. C'est une drôle de période.
> <cite>— Martin Dufresne</cite>

Maxime lui a décrit le point dur chez nous. Ce n'est plus le développement, où nous avons accepté une certaine capacité
à lâcher prise en mettant notre énergie sur l'encodage de nos pratiques dans le harnais (_les règles et les outils que
nous donnons aux agents_). C'est tout ce qu'il faut prévoir en amont : les itérations, les maquettes, les explorations,
la validation technique. D'où ce que nous appelons entre nous **le mur des décisions**, ces arbitrages qui se
répartissaient avant sur une semaine entière et qui tiennent maintenant dans une journée.  
_Restait à savoir quel **garde-fou** il s'était trouvé, lui._

_« Actuellement, je n'en ai pas. Je pense qu'on vit tous un peu ce syndrome du mur. »_ Il teste beaucoup de nouvelles
approches pour l'éviter, sans résultat pour l'instant : le mur arrive à chaque fin de journée, et _« le lendemain, c'est
un recommencement »_.

Comme la pression vient du fait qu'il faut produire, on multiplie les tentatives plutôt que de s'arrêter pour chercher :
_« Je pense que si on prenait une pause pour réfléchir à comment justement régler ce problème-là, on finirait par le
trouver, mais le flot constant de travail fait qu'on ne le prend jamais. »_ Le seul temps disponible, ce sont les
vacances ou les congés, _« et on n'a plus le goût »_. Il termine là-dessus :

> Finalement, on est toujours dans une roue. Je pense qu'il va falloir prendre le temps de sortir de la roue pour
> réussir à trouver une nouvelle approche.
> <cite>— Martin Dufresne</cite>

### Quand le projet est pensé sans l'équipe

Si les décisions se concentrent en amont, qui les détient ? Eva, cheffe de projet chez Elao, a posé la question qui la
concerne directement : les développeurs rédigent désormais les tickets de spécification qu'elle cadrait avant eux, et la
marche à franchir pour entrer dans un projet dont on n'a défini ni les specs ni les tickets ne cesse de monter.

Martin répond en indépendant qui embarque dans beaucoup d'équipes. Les chargés de projet qui réussissent le mieux à
entrer dans un projet s'assoient avec tous les membres de l'équipe et se font expliquer le projet étape par étape, du
début à la fin. Cela peut être long : _« J'ai déjà vu que ça prenait la semaine au complet. »_ Pour lui ce n'est pas une
perte de temps mais un gain : en sachant le pourquoi de chaque décision, on a un portrait global du projet, le chargé de
projet peut beaucoup mieux outiller ses équipes, et il est lui-même moins perdu.

Le problème, souvent, est plus haut. Sur les projets où il a travaillé au Québec, _« tout le process créatif est fait
indépendamment des développeurs »_, et le chargé de projet arrive quasiment en même temps qu'eux. Les devs découvrent
alors ce qu'il va y avoir dans l'interface, et _« finalement, tout le monde y perd »_.

Nous lui avons décrit ce qui a changé chez nous. Nous avions pour habitude d'embarquer tout le monde dès la phase de
conception, mais l'IA a changé la donne en avant-vente : elle capte et structure les besoins plus vite et plus finement
qu'avant. Résultat, le backlog est déjà écrit et les grosses fonctionnalités validées au moment de passer le projet à
l'équipe, et une seule personne en détient la connaissance.

Sa réponse : _« quand une seule personne détient toute la connaissance du projet, ça fait un petit peu l'effet du
téléphone arabe »_. Quand vient le moment de transmettre, _« chacun a un petit bout, mais personne n'a l'ensemble »_. Il
invite à revoir ce fonctionnement, _« parce qu'au final le projet écope, tout le monde écope »_. Et il ajoute ce que
cela produit sur une équipe : _« ça met un stress inutile sur une équipe qui doit rendre un projet pensé sans elle »_.

## Où est passé le plaisir ?

Dans [_« Le coût caché du codage de l'IA »_](https://martindufresne.substack.com/p/le-cout-cache-du-codage-de-lia),
Martin parle du **flow**, cet état dans lequel on rentre lorsque le défi rencontre la compétence à un juste équilibre,
et du risque d'aboutir à des développeurs très productifs mais étrangement détachés. Maxime lui a raconté ce que ça
donne chez nous : la joie s'est déplacée en fin de journée, quand on constate que l'IA a produit quelque chose de
satisfaisant, plus vite et avec une finition parfois au-delà de ce que nous savions fournir avant. Sauf que c'est elle
qui l'a produit.  
_Restait à savoir où lui trouve encore **de la joie** et **de la passion**._

Sa réponse tient dans la niche. Il travaille depuis près de deux ans sur le sujet de la **morphogénèse logicielle** : un
petit système d'exploitation qui s'auto-répare.

> On peut lui bousiller tous ses outils de démarrage, il détecte l'anomalie et
> s'auto-répare en se basant sur l'historique de ses voisins, puis redémarre.  
> Et ça fonctionne merveilleusement bien. ☺️

Le système utilise ce qu'on appelle des **automates cellulaires**. Chaque automate agit de son côté, un bit à la fois,
sur sa petite portion du problème. C'est la somme des actions locales qui produit le comportement d'ensemble. Et ça ne
consomme presque rien.

C'est un sujet sur lequel presque personne ne travaille, et c'est exactement ce qui le lui rend praticable pour trouver
du plaisir : _« j'ai beau utiliser l'intelligence artificielle, elle n'a pas vraiment de réponse à mes questions »_. Là
où l'outil n'a rien à proposer, l'espace de créer revient :

> J'arrive à me libérer l'esprit, puis à créer avec ce projet-là.

C'est aussi pour cela qu'il a laissé le développement de côté depuis plusieurs mois, au profit de ce projet de recherche
et de l'enseignement. L'enseignement lui rend de la créativité par un autre chemin : il cherche pour ses étudiants des
idées de projets qu'ils ne s'attendent pas à voir sur Internet, et leur fait construire en ce moment un agent IA
complètement autonome, capable d'aller magasiner pour lui. _« Pour eux, c'est magique. »_ Pour lui, c'est une sortie du
quotidien : _« ça me permet de tester des idées que je n'aurais normalement pas pris le temps de tester »_. Il pousse le
même réflexe jusqu'aux petits projets qu'il prépare pour ses cours, qu'il fait sans IA, _« simplement pour avoir un peu
le plaisir »_.

Ailleurs, il ne s'en cache pas : _« Mais sinon, je n'ai plus tant de plaisir dans le développement. »_

Ce qu'il a perdu dans le développement, c'est le défi. _« À l'époque, il y avait des défis. Tu rencontrais un bug
majeur, tu avais le défi de passer par-dessus et de réussir à le régler. »_ Aujourd'hui l'IA trouve la solution, et il
ne reste que la fierté sans son objet : _« tu vas juste être fier de dire “j'ai réglé le problème”, mais tu n'as rien
réglé. La machine l'a réglé pour toi, et toi, de ton côté, tu as juste regardé ce qu'elle a fait. »_

Eva a relancé sur cette impression d'être devenu un simple exécutant, qui lit et valide sans plus coder. Il la retrouve
dans les agences : _« il y a beaucoup de frustration dans les agences du fait qu'on ne fait plus vraiment notre métier
»_, alors que _« le plaisir qu'on avait en choisissant ce métier, c'était justement de développer, de créer, d'imaginer
»_. Avec une conséquence qu'il constate autour de lui : _« il y en a plein qui abandonnent le métier parce qu'ils n'ont
plus aucun plaisir à le faire. On perd des gens qu'on n'aurait normalement pas perdus. »_

L'open source, où il a contribué, lui donne moins de plaisir pour une autre raison : _« tout le monde semble être devenu
expert avec l'IA »_, et _« on ne peut plus savoir qui a fait le travail »_. Ce qu'il observe : _« Il y a tellement de
gens qui disent “ah, je sais, je sais”, mais au final, personne ne sait vraiment. »_ Ceux qui savent vraiment, selon
lui, prennent du recul et se tiennent à l'écart de ce flot continu. _« On les entend de moins en moins parler. Et c'est
ceux qui ont le moins de compétences qui parlent le plus. Au final, ça nuit à tout le monde. »_

## Le web va-t-il finir par tous se ressembler ?

Notre designeuse nous a remonté la même inquiétude, côté UI et UX. L'IA fait l'affaire pour tester rapidement quelques
parcours, mais sur la partie interface, elle craint de perdre en créativité si elle ne fait pas elle-même ses maquettes,
et de ne plus trouver de sens dans un métier justement très créatif. Eva a demandé à Martin comment il voit ça, lui.

Diplômé en graphisme et ancien intégrateur, il tient à ce que l'humain garde la main : _« Ce n'est pas vrai que la
machine sait comment construire une interface. »_ Ce ne sont que de gros algorithmes qui vont par probabilité ; comme
ils ont analysé beaucoup d'interfaces, toutes les interfaces se ressemblent et reprennent le même concept, sans être
forcément performantes. Là où _« l'humain sait très bien que dans tel cas, l'usager va être gagnant en mettant le bouton
à cet endroit-là plutôt qu'à cet endroit-là »_, l'IA _« ira selon la plus grande probabilité »_.

Le constat qu'il en tire :

> Quand on regarde les designs récents, tout se ressemble. On dirait qu'ils sont tous faits sur le même moule.
>
> Dans ce domaine-là, moins on utilise l'IA, plus on a un produit de qualité pour les usagers.
> <cite>— Martin Dufresne</cite>

Il reconnaît que cela prend plus de temps, tout en estimant qu'on en retire des bénéfices.

Maxime lui a opposé un souvenir : il y a 10 à 15 ans, on tenait un peu le même discours face aux premiers frameworks
CSS, et ils ont finalement participé à faire émerger des standards à partir desquels le web s'est structuré et
professionnalisé, sans pour autant toujours uniformiser les créations. La solution est-elle de se passer de l'IA sur ces
aspects, ou de trouver comment encoder sa personnalité et sa créativité dans les process qu'on lance avec elle ?

_« Je pense que c'est un peu un mix des deux. »_ Le problème, tel qu'il le voit, tient plus à la phase qu'à l'outil :
nous serions dans _« une zone tampon, où les gens ne font pas la deuxième partie, celle de mixer la créativité humaine
avec l'IA »_. Une période où l'on délègue trop, _« probablement parce que c'est une nouvelle technologie et qu'il faudra
encore plusieurs années avant que la hype descende »_.

Il en veut pour précédent l'époque des sites en **Macromedia Flash**. Au début, _« les sites se ressemblaient presque
tous »_, puis _« la créativité et les nouveaux outils ont embarqué »_, et on a eu quelque chose de plus personnalisé. Il
attend le même cycle sur trois ou quatre ans, peut-être un peu moins : _« la base, la fondation, on va la faire faire
par l'IA, puis tout ce qui est le process humain, la spécificité, va revenir dans la balance. »_

## Apprendre quand la machine a déjà la réponse

Dans
[_« La fin du code : Que reste-t-il quand le logiciel ne vaut plus rien ? »_](https://martindufresne.substack.com/p/la-fin-du-code-que-reste-t-il-quand),
Martin décrit le passage de l'artisan du code à **l'architecte de l'intention**. Cela résonne chez nous, où la part de
temps passée sur la production de code recule au profit de la conception et des ateliers. Un junior doit pourtant
continuer à maîtriser et comprendre ce qu'il produit, et Maxime lui a demandé comment il transmet ça.

Il met l'emphase sur la compréhension fine des problèmes plutôt que sur le code : _« Le code, la machine va toujours le
produire plus rapidement. »_ Il donne donc à ses étudiants du code qui ne fonctionne pas, et les force à se passer de
l'IA comme d'Internet. Sans chercher forcément à ce qu'ils le résolvent : _« je veux qu'ils réfléchissent à pourquoi
j'ai ce problème-là, puis ce seraient quoi les pistes de solution pour le résoudre »_.

_« Au début ils détestent. Ils me détestent. Mais au final, ils me remercient »_, parce qu'à force ils finissent par
comprendre l'architecture du produit. Il n'en fait pas pour autant une certitude : _« Est-ce que c'est la bonne voie ?
Je ne sais pas. »_

Il fait le même exercice côté visuel : deux ou trois images, et trois concepts d'interface à construire sans IA. Son
observation : _« je me rends compte que les gens ne sont comme plus capables, on a comme perdu cette créativité-là »_.
Même chose côté développement, avec un junior privé d'Internet et d'IA devant un problème : _« Tu le mets devant un mur
blanc : il n'est plus capable de parler, plus capable de réfléchir. »_ D'où sa façon de résumer l'exercice : _« il
s'agit de forcer les gens à réutiliser leur cerveau »_.

Il évalue donc rarement ses étudiants sur le fait qu'ils produisent le meilleur code, ou même du code fonctionnel, mais
_« vraiment sur la réflexion qu'ils ont par rapport à l'interface ou par rapport au code »_. L'effet qu'il observe : _«
ils ont moins peur, ils se permettent plus de créativité, plus de folie, en sachant que ce n'est pas grave s'ils font
des erreurs. Au moins, ils ont réfléchi. »_

Le début de carrière, lui, a changé de nature. Avant, on arrivait dans une boîte, on voyait les seniors, _« c'était nos
mentors et notre objectif »_. Aujourd'hui, _« il faut combattre l'IA, il faut combattre les seniors… Finalement, ils
deviennent tellement stressés qu'ils ne savent plus trop où aller. »_

Ce vers quoi il pousse ses juniors, peu importe le domaine : chercher à retrouver du plaisir à faire les choses. Avec le
plaisir, on redevient créatif, on apprend plus facilement, et on pousse ses capacités plus loin. _« En tout cas, c'est
ma vision de la chose. »_

## Écrire à la main, pour réfléchir

L'écriture de Martin est venue de l'enseignement : les mêmes questions revenaient chez ses élèves débutants, alors il a
lancé une newsletter pour vulgariser des concepts assez techniques et les rendre accessibles à n'importe qui. Dans
[_« Plus de gens devraient écrire »_](https://martindufresne.substack.com/p/plus-de-gens-devraient-ecrire), il défend
l'écriture comme exercice de pensée plutôt que comme moyen de se faire lire. Maxime lui a demandé pourquoi il la
plébiscite encore autant à l'heure des contenus générés en masse.

Sa réponse : _« Principalement parce que lorsqu'on écrit, on réfléchit. Ça nous force à réfléchir à ce qu'on dit. »_ À
écrire moins, observe-t-il, on devient plus impulsif : _« on dit quelque chose, puis après on réfléchit »_.

Sa pratique tient d'un artisanat qu'on qualifierait presque d'anachronique 😁 : _« Je suis encore de la vieille école.
J'écris beaucoup à la main, sur papier, surtout quand je développe des projets. »_ Il lui arrive d'écrire tout un projet
à la main, graphiques compris. _« Souvent, pendant que je le fais, je me rends compte de ce qui n'est pas logique dans
le projet. »_ Interrogé là-dessus, il répond ne rien déléguer de ce processus à l'IA.

Sa newsletter n'a aucune publicité et une centaine d'abonnés, et il ne pousse pas plus que ça : _« si les gens
accrochent, ils s'inscrivent, c'est correct »_. Ce qu'il en retire : _« ça me permet de réfléchir et de parler des
sujets qui me tiennent à cœur. Si ça peut toucher des gens, tant mieux. Mais j'écris d'abord pour moi. »_

### Se satisfaire du nécessaire

Dans [_« Soixante-dix pour cent »_](https://martindufresne.substack.com/p/soixante-dix-pour-cent), Martin défend **la
règle des 70 %** de satisfaction contre la recherche de la perfection : s'acharner sur les derniers pourcents coûte un
temps fou pour un gain marginal. Maxime lui rapporte sa propre vision aujourd'hui, bien qu'ayant du mal à l'appliquer :
viser 100 % plutôt que se satisfaire de moins, c'est souvent une façon de ne pas trancher, et parfois un frein pour se
lancer ou se sentir légitime.  
_Y arrive-t-il vraiment, lui ?_

_« Oui, j'arrive à l'appliquer aujourd'hui. »_ Et il part du même endroit que nous : _« Moi aussi, j'ai été très, très
perfectionniste : je visais toujours le 100 %. »_ Ce qui l'a fait basculer n'est pas une théorie mais une observation :
quel que soit le produit, il devait retravailler dès la mise en production. _« Donc je n'arrivais jamais, de toute
façon, au 100 %. »_ Le bénéfice est autant psychologique que productif : _« Ça m'a libéré énormément de stress. Et ça
m'a permis d'avancer beaucoup plus vite sur mes travaux. »_

Il se méfie d'ailleurs des règles trop fermes, y compris des siennes : _« Je n'ai pas tant de règles que ça. Je m'en
crée pour voir si elles tiennent. »_ Les 45 minutes d'écriture quotidienne ont tenu les 10 à 12 premiers numéros, avant
qu'il les fasse sauter en se trouvant trop limité dans ce qu'il voulait expliquer.

Sur son procédé d'écriture : il couche les points principaux, les traite tous, puis _« une fois que j'ai écrit la
conclusion, je sais que l'article n'est pas complet. Mais je me dis : il est suffisamment complet pour que les gens en
tirent des bénéfices, donc je le pousse quand même. »_

> Je n'écris aucun article dont je suis 100 % satisfait.
> <cite>— Martin Dufresne</cite>

## L'IA qu'on choisit, l'IA qu'on subit

Connaît-il cette culpabilité de prendre le temps de faire soi-même quand l'outil irait plus vite ? _« Oui, oui, oui, je
la subis, je la subis souvent. »_ Il trie selon la nature de la tâche. Les tâches chronophages qui reviennent tout le
temps, _« qu'elle les fasse, ça ne me dérange pas »_. Le créatif, l'architectural, tout ce qui demande de la réflexion,
il ne l'utilise pas du tout. _« Est-ce que ça fait de mes produits des meilleurs produits ? Je ne pense pas, mais ça
fait au moins des produits plus humains. »_

Eva l'a ensuite interrogé sur l'empreinte écologique, la nôtre ayant fortement augmenté depuis que nous utilisons ces
outils au quotidien. Lui n'utilise pas de gros modèles de langage, seulement de petits modèles locaux, moins gourmands.
Mais sa critique ne porte pas d'abord sur l'IA qu'on choisit, elle porte sur celle qu'on subit, citant ces résumés
générés (les Google Overviews) qui coiffent les résultats de recherche, plus récemment en Europe : _« Est-ce que c'était
vraiment nécessaire ? »_ Sa formule résume le problème : _« Aujourd'hui, tout est prétexte à consommer de l'IA, et avec
les impacts qui viennent avec. »_ Il salue l'approche de Firefox, _« qui permet de désactiver entièrement l'IA du
navigateur »_, et pointe Chrome, où l'option a disparu : _« C'est tu l'utilises ou tu l'utilises. »_

Son pronostic n'est pas optimiste : _« À terme on va foncer dans le mur. »_ Il ne croit pas aux efforts que les grandes
compagnies annoncent, faute d'intérêt financier, et attend une législation pour encadrer tout ça, _« mais vu la vitesse
des gouvernements, on va attendre longtemps »_.

## Les quinze minutes où l'on a vraiment du plaisir

Dans
[_« L'idée de devoir réfléchir intensément me manque »_](https://martindufresne.substack.com/p/lidee-de-devoir-reflechir-intensement),
Martin décrit deux traits qui cohabitent en lui : le Bâtisseur, celui qui veut créer et livrer avec pragmatisme, et le
Penseur, qui a besoin de ruminer un problème difficile pendant des jours. Maxime s'est reconnu dans une variante plus
têtue, l'acharné qui s'accroche jusqu'à la solution élégante, et qui constate que cette force est aujourd'hui moins
récompensée : chacun peut produire à toute vitesse une solution ad-hoc qui fonctionne. Ce qui devient accessible, ce
n'est pas le savoir, c'est le fait de s'en passer.

Martin le rejoint : _« Oui, moi aussi, j'ai été longtemps à m'acharner. Je m'acharne encore, d'ailleurs. »_ Son
acharnement s'est simplement déplacé vers de petites choses. Aujourd'hui, il a tendance à se dire qu'il va créer une
fonction qu'on retrouve à peu près partout, _« mais y ajouter un petit peu ma touche »_ : parfois très subtil, un effet
en survol sur une icône, unique au produit. _« Dans le fond, ça m'a pris 15 minutes. Mais c'est 15 minutes où j'ai eu
vraiment du plaisir. C'est ma façon à moi d'aller chercher ce petit côté magique. »_

Il se décrit comme _« le petit gars acharné qui s'amuse à essayer de réinventer le monde sur Internet chaque jour »_, et
se revendique _« encore un grand enfant »_ : il navigue, voit une icône avec un petit effet, et _« waouuuuh »_. Sa
justification : _« On a cette compétence, cette connaissance-là, qui n'est pas donnée à tous. Donc aussi bien en tirer
du plaisir. »_

Il cache aussi des _easter eggs_ dans les gros projets, des portions cachées. Dans certains, une combinaison de touches
donnait un petit jeu de Sudoku : _« On s'entend, quand on travaille dans un tableur toute la journée, avoir de quoi se
détendre sans quitter le projet, ça peut être plaisant. »_ Les clients ne le demandent jamais, mais ils trouvent que _«
ça ajoute un petit côté humain à leur outil »_, et _« c'est très rare qu'on me demande de le retirer »_.

## Ce qui lui manque le plus

Nous lui avons demandé s'il gardait un regret de l'époque d'avant. Ce n'est pas la technique : c'est l'échange. _«
C'était l'époque où on échangeait vraiment. Ça pouvait être quelque chose de très, très petit, mais tout le monde se
partageait la connaissance. »_ Aujourd'hui, _« les gens publient à tout va. Des choses qui n'ont plus vraiment de saveur
»_, alors qu'avant _« les découvertes avaient une petite saveur »_.

> Tout le monde peut désormais tout faire rapidement, sans réfléchir, et ça, ça manque. Il n'y a plus de réflexion, de
> fond, il n'y a plus de communication, il n'y a plus de partage.
> <cite>— Martin Dufresne</cite>

## Pour finir

Nous cherchions des méthodes, il n'en avait pas de toute faite. Reste un conseil, qui vaut autant pour un junior devant
un mur blanc que pour un senior qui ne reconnaît plus son métier :

> À partir du moment où tu as du plaisir à faire quelque chose, je pense que la créativité, puis la facilité
> d'apprendre, puis de pousser tes capacités, revient.
> <cite>— Martin Dufresne</cite>

Nous remercions chaleureusement Martin pour son temps, sa franchise et sa bonne humeur. Nous avons grandement apprécié
cet échange, très enrichissant. Ce qui devait être une interview d'une heure est devenu une conversation à trois, où
nous avons autant parlé de nos propres impasses que des siennes. Nous attendons la suite de sa morphogénèse logicielle,
qu'il espère terminer d'ici la fin de l'automne.

Et il nous a laissé une invitation que nous vous transmettons : si vous avez des idées de sujets pour sa newsletter,
écrivez-lui !

### Pour suivre Martin

- [Son site](https://martindufresne.com/)
- [Son blog](https://martindufresne.com/blog/)
- [Sa newsletter sur Substack](https://substack.com/@martindufresne)
- [Son compte Bluesky](https://bsky.app/profile/mdufresne.bsky.social)

### Les voix qu'il nous recommande

- **Paul Gauthier**, créateur d'Aider, pour suivre _« l'évolution du pair programming en ligne de commande et la façon
  dont les LLM peuvent s'intégrer dans le flux du développeur »_ : [LinkedIn](https://www.linkedin.com/in/paulgauthier/)
  et [X](https://x.com/paulgauthier).
- **Ethan Mollick**, professeur aux États-Unis, qui _« parle énormément de tout ce qui est de la posture à adopter face
  à l'IA, l'expérimentation »_ : sa newsletter [One Useful Thing](https://www.oneusefulthing.org/) et
  [X](https://x.com/emollick).
- **Clément Delangue**, de Hugging Face : _« Parle beaucoup du mouvement open source, de la souveraineté et des modèles.
  »_ Son [LinkedIn](https://www.linkedin.com/in/clementdelangue/).

<!-- mode: entretien -->

Ce mois-ci nous sommes allés à la rencontre de [Martin Dufresne](https://martindufresne.com/), une trentaine d'années
dans l'informatique au compteur, une newsletter technique chaque semaine, et un vrai talent pour les analogies
parlantes. Nous lui laissons la parole.

> *Propos recueillis par Eva Quentin et Maxime Steinhausser. Les réponses de Martin sont reprises de l'entretien et
> éditées pour la lecture : coupes, resserrement des hésitations et des répétitions, quelques reformulations légères.
> Ses tournures québécoises sont conservées. Des paragraphes précèdent chacune de nos questions pour apporter un peu de
> contexte durant l'entretien et faire le lien avec les articles de Martin.*

## Ses travaux

### « Ça fait 30 ans aujourd'hui que je fais de l'informatique »

> **Eva :** C'est l'une de nos toutes premières interviews dans ce format : nous donnons la parole à des personnes que
> nous suivons et qui nous inspirent dans notre veille. Est-ce que tu peux te présenter en quelques mots : qui tu es,
> ton parcours, ce sur quoi tu travailles aujourd'hui ?

J'ai commencé comme développeur de jeux vidéo pour enfants, puis je suis allé vers le web. Ça fait 10-12 ans que je
travaille principalement en intelligence artificielle, et peut-être 7-8 ans que je fais de l'informatique quantique. Je
fais de la recherche indépendante de mon côté.
Depuis plusieurs années, je dirais peut-être 8-9 ans, je suis formateur : j'enseigne autant en entreprise qu'au sein
d'écoles. Et je fais un petit peu de vulgarisation, parce que l'IA, c'est bien le fun, mais on a rarement l'occasion de
comprendre comment ça fonctionne.
À tout ça, j'ai aussi jumelé un petit peu de cybersécurité et d'architecture logicielle. Ça fait donc 30 ans aujourd'hui
que je fais de l'informatique : on a eu le temps de se former 😁

### « Et si j'allais vulgariser simplement des concepts un peu techniques ? »

> C'est un collègue qui nous a fait découvrir Martin, il y a environ deux ans, via une newsletter qui avait relayé un de
> ses articles. Il nous en a parlé au moment où nous lancions notre format de revue de presse, et depuis nous l'y
> intégrons régulièrement tant ses articles nous passionnent.  
> Ce qui nous accroche : des sujets techniques, portés par des réflexions ancrées sur ses propres travaux, et un vrai
> talent pour les analogies parlantes.

> **Maxime :** Comment tu présenterais ta démarche d'écriture ? C'est quoi le fil rouge entre tous ces sujets que tu
> abordes ?

L'écriture m'est venue du fait que, comme j'enseigne beaucoup à des gens qui débutent dans le métier, on me posait de
nombreuses questions. Et ce n'est pas toujours évident d'amener un sujet pour qu'il soit compris lorsqu'il est assez
technique. Donc je me suis dit : je vais partir d'une newsletter, puis je vais simplement vulgariser des concepts qui
sont assez techniques, et je vais essayer de les rendre accessibles à n'importe qui. Peu importe que tu sois dans le
domaine ou pas.

Au début, le concept de la newsletter était assez simple. Je voulais prendre 45 minutes le matin pour écrire tout ce que
j'avais dans la tête. Une fois que c'était fini, c'était fini. Finalement, les sujets sont devenus de plus en plus
complexes. Souvent, je passe 4 à 6 heures pour vulgariser tout le contenu, puis faire une petite newsletter, pas bien
longue, une fois par semaine.

Je me base sur ce que les gens me posent comme question, ou sur ce que je vois sur les forums. Je me dis : ah bien,
c'est un sujet qui revient régulièrement, donc je l'aborde, puis j'essaie de le vulgariser le mieux possible.

### « Le système se base sur l'historique de ses voisins pour s'auto-réparer »

> Parmi les articles qui nous ont particulièrement marqués :
> [« La Résilience par la Perte : Pourquoi mon code doit mourir pour survivre »](https://martindufresne.com/blog/la-r%C3%A9silience-par-la-erte_pourquoi-mon-code-doit-mourir-pour-survivre/)
> et le concept de la **morphogénèse logicielle**. Il y a 2 ou 3 ans encore, les liens qu'il tisse entre le logiciel et
> le vivant auraient pu passer pour de la science-fiction. Avec la vitesse actuelle, ça nous semble tout à fait
> crédible.

> **Maxime :** La morphogénèse logicielle, c'est quoi exactement, et où tu en es sur le sujet ?

Ça fait partie des travaux sur lesquels je travaille depuis plusieurs mois, presque deux ans. C'est un concept auquel je
suis venu surtout à cause de la cybersécurité : je travaille sur un petit système d'exploitation qui s'auto-répare.

Par exemple, si tu démarres mon mini système d'exploitation, puis que tu bousilles complètement tous ses outils de
démarrage, en principe, le système d'exploitation ne fonctionnerait plus. Mais le système va détecter qu'il y a eu
quelque chose, puis il va se baser sur l'historique de ses voisins pour s'auto-réparer lui-même. Pour pouvoir redémarrer
automatiquement. Et ça fonctionne merveilleusement bien.

Je l'ai pensé plus dans un contexte de cybersécurité : où est-ce qu'on se fait attaquer. Dans le fond, ils vont
neutraliser le virus pour ensuite réparer les portions qui ont été corrompues, puis que le système soit propre tout le
temps. Pour l'instant, les recherches que je fais fonctionnent très bien, mais il reste encore beaucoup de boulot. C'est
un sujet assez récent, et il n'y a pas beaucoup de monde qui travaille sur ce concept-là.

> **Maxime :** Tu t'imaginais déjà un tel système il y a deux ou trois ans ?

Non, non. C'est vraiment à cause de certains incidents, qui me sont arrivés, qui sont arrivés à des collègues avec qui
je travaille, que je me suis dit : il doit y avoir une approche qu'on pourrait prendre pour auto-réparer nos machines.

> **Maxime :** Tu y vois d'autres applications qu'à la cybersécurité ?

Tout ce qui est morphogénèse, dans le fond, c'est rendre la machine autonome, capable de s'auto-réguler. Je suis parti
sur le créneau de la cybersécurité, mais je suis persuadé qu'il y aura bien d'autres applications.

C'est très léger en termes de consommation de ressources : ça utilise ce qu'on appelle des **automates cellulaires**,
qui modifient un bit à la fois. Chaque automate agit de son côté, sur sa petite portion du problème, un peu comme une
équipe de travailleurs indépendants. C'est la somme de ces actions locales qui produit le comportement d'ensemble. Et ça
ne consomme presque rien.

## Ses constats

### « L'IA ne dégage pas de temps, ça en prend plus »

> Dans
> [« La disparition des temps morts : l’autre visage de la fatigue liée à l’IA »](https://martindufresne.substack.com/p/la-disparition-des-temps-morts-lautre),
> Martin décrit ce que produit la délégation des tâches faciles ou à faible valeur à des agents IA : il ne reste que des
> tâches à forte charge de réflexion, sans ces petits moments qui laissaient le cerveau souffler, mais avec le sentiment
> d'avancer.
>
> Nous vivons pleinement cela à l'agence, au point que des développeurs ont tiré la sonnette d'alarme. Nous y avons
> consacré une journée de team building et des ateliers pour que chacun s'exprime. Deux pistes en sont sorties : des
> moments de respiration en dehors du cœur de métier (par exemple aider une association, travailler une passion, prendre
> du temps pour soi) et un rythme inspiré de **Shape Up** : trois semaines de production puis une semaine de relâche.
> Nous nous cherchons encore.

> **Eva :** Comment tu gères cette fatigue cognitive au quotidien ? Est-ce que tu as trouvé des garde-fous qui tiennent
> dans la durée ?

Je travaille beaucoup le matin ; c'est souvent là que je suis le plus performant. L'après-midi, c'est vraiment une zone
tampon où je fais du sport, je me change les idées pour me ressourcer. Puis le soir, j'enseigne. Donc je n'ai pas
vraiment davantage de temps libre, mais je travaille davantage avant midi. Ce qui me donne à peu près des demi-journées
de travail, mais du travail très intensif.

C'est vrai qu'avec l'arrivée de l'IA, tout le monde pensait que ça allait dégager du temps. Finalement, ça en prend
plus. Comme l'IA fait une grosse partie du travail, on se donne de plus en plus d'ouvrages à faire. On ne s'en rend même
pas compte. Les gens prennent deux à trois fois plus de tâches en se disant : ce n'est pas grave, l'IA va le faire.
Puis on se retrouve à la fin de la semaine bien plus fatigué qu'on ne l'était avant.

Donc je pense qu'à terme, votre idée de réfléchir à une nouvelle approche de travail, ça va être gagnant pour tous.

Puis au niveau de la créativité, on y perd beaucoup. On est comme sur le mode pilote automatique, tout le temps, à
toujours générer du travail, sans vraiment réfléchir. On n'arrête pas de produire, de produire, de produire et
finalement, tout se ressemble. Il n'y a pas vraiment de renouvellement, parce qu'on n'a plus le temps de renouveler nos
propres idées. C'est une drôle de période.

> **Maxime :** Cette intensité et le manque de recul sur ce qu'on fait, je le vis entièrement. Pourtant, il y a aussi un
> pendant de l'IA qui nous permet davantage d'exploration, là où on ne se le permettait pas avant. Parfois on arrive à
> équilibrer avec ça. Mais la volumétrie à absorber reste très lourde cognitivement. C'est ce que tu décrivais dans
> [« Pourquoi les agents IA vont saturer vos semaines (et non les alléger) »](https://martindufresne.substack.com/p/pourquoi-les-agents-ia-vont-saturer) :
> le volume de choses à traiter explose, mais notre rythme biologique, lui, ne s'adaptera pas.

Surtout pour les agences marketing, où beaucoup de créativité doit sortir. Si on est étouffé à un moment donné, on est
moins productif, et ça nuit à tout le monde.

> **Maxime :** Chez nous, avec l'IA, le point dur n'est plus tellement le dev, où on a accepté une certaine capacité à
> lâcher prise et gagner en confiance, en mettant notre énergie sur l'encodage de nos pratiques dans le harnais (_les
> règles et les outils que nous donnons aux agents_). C'est plutôt le fait de tout prévoir en amont et de passer plus de
> temps sur la conception : les itérations, les maquettes, les explorations, la validation technique. Durant la journée,
> il y a ce que j'appelle **le mur des décisions**, que tu te prends dans la face, là où avant ces mêmes décisions
> étaient réparties sur une semaine entière. Et à la fin de la journée, tu es épuisé, parfois sans même avoir encore
> produit quelque chose de concret. Est-ce que toi, tu as un garde-fou pour t'empêcher d'en arriver là ?

Actuellement, je n'en ai pas. Je pense qu'on vit tous un peu ce syndrome du mur. Je travaille beaucoup à tester des
nouvelles approches pour m'éviter ce mur-là, mais jusqu'à présent, non ; il arrive à chaque fin de journée. Et je trouve
ça dommage, parce que tu finis ta journée en te disant : finalement, je n'ai pas vraiment de solution, alors que je
pensais trouver une solution. Puis le lendemain, c'est un recommencement.

Comme la pression vient tout le temps du fait qu'on doive produire, on fait davantage des tentatives que d'essayer de
trouver et de réfléchir vraiment à des solutions. Je pense que si on prenait une pause pour réfléchir à comment
justement régler ce problème-là, on finirait par le trouver, mais le flot constant de travail fait qu'on ne le prend
jamais. Et le seul temps qu'on a, c'est quand on tombe en vacances ou en congés, et on n'a plus le goût.

Finalement, on est toujours dans une roue. Je pense qu'il va falloir prendre le temps de sortir de la roue pour réussir
à trouver une nouvelle approche.

### « Le développement est moins plaisant aujourd'hui qu'à l'époque »

> Dans [« Le coût caché du codage de l'IA »](https://martindufresne.substack.com/p/le-cout-cache-du-codage-de-lia),
> Martin parle du **flow** : l'état dans lequel on rentre lorsque le défi rencontre la compétence à un juste équilibre
> et où l'on est complètement absorbé. Il parle aujourd'hui du risque d'aboutir à des développeurs très productifs mais
> étrangement détachés, jamais passionnés. Nous le ressentons : la fatigue et la satisfaction ont changé de nature. Là
> où l'on se prenait la tête trois heures sur un bug, où l'on finissait exténués mais fiers d'avoir trouvé, et où
> s'arrêter était naturel, aujourd'hui on lance un agent, on vérifie, on relance, et on ne trouve plus de raison de
> s'arrêter. Ni dans la joie d'un problème résolu à la sueur de son front, ni dans la satisfaction du travail accompli.
> C'est l'agent qui travaille après tout… non ?

> **Maxime :** Il y a quelques années, je trouvais du sens dans l'open source ou dans le fait de trouver des solutions
> et mettre certains de mes développements et réflexions dans les mains d'un plus grand nombre. Je considérais mon
> métier comme une passion, et je rentrais souvent dans cet état de flow. Avec l'IA, on a un peu perdu ça. La joie s'est
> déplacée en fin de journée, quand on constate que l'IA a produit quelque chose de satisfaisant, qu'on a répondu à une
> demande, plus rapidement, et avec un effort de finition même au-delà de ce qu'on pouvait fournir auparavant. Mais
> c'est l'IA qui l'a produit, ce n'est même plus vraiment toi. Toi, comment tu arrives à retrouver aujourd'hui de la
> joie, de la passion, malgré l'IA ?

C'est un peu le pourquoi de mon projet de morphogénèse logicielle : c'est un sujet très niche. Presque personne ne
travaille dessus, donc j'ai beau utiliser l'intelligence artificielle, elle n'a pas vraiment de réponse à mes questions.
J'arrive à me libérer l'esprit, puis à créer avec ce projet-là. Mais sinon, je n'ai plus tant de plaisir dans le
développement.

Moi aussi, j'ai participé à des projets open source, et avec l'IA, j'ai moins de plaisir à y contribuer. Surtout parce
que tout le monde semble être devenu expert avec l'IA. Et je trouve ça dommage, parce qu'on ne peut plus savoir qui a
fait le travail. Il y a tellement de gens qui disent « ah, je sais, je sais », mais au final, personne ne sait vraiment.

Ceux qui savent vraiment prennent du recul, parce qu'ils préfèrent se tenir à l'écart de ce flot continu. On les entend
de moins en moins parler. Et c'est ceux qui ont le moins de compétences qui parlent le plus. Au final, ça nuit à tout le
monde.

> **Eva :** Est-ce que cette frustration te pousse à mettre complètement le développement de côté, pour te concentrer
> sur tes recherches et l'enseignement, ou est-ce que tu tiens à garder cet aspect ?

Depuis plusieurs mois, j'ai laissé un peu le dev de côté pour me concentrer sur la morphogénèse et l'enseignement, oui.
J'ai beaucoup de plaisir à enseigner, parce que ça me permet d'être plus créatif : j'essaie de trouver pour mes
étudiants des idées de projets qu'ils ne s'attendent pas à voir sur Internet.
En ce moment, je donne une formation sur l'intelligence artificielle dans le contexte du développement web, et je leur
fais construire un agent IA capable d'aller magasiner pour moi. J'y vais à fond : l'agent est complètement autonome, il
prend ses propres décisions. Pour eux, c'est magique. Et moi, ça me sort de mon quotidien, ça me permet de tester des
idées que je n'aurais normalement pas pris le temps de tester.

Mais c'est sûr que le développement est moins plaisant aujourd'hui qu'à l'époque. À l'époque, il y avait des défis. Tu
rencontrais un bug majeur, tu avais le défi de passer par-dessus et de réussir à le régler. Aujourd'hui, avec l'IA,
quand il y a un bug majeur, elle trouve la solution. Tu n'as rien fait pour, tu vas juste être fier de dire « j'ai réglé
le problème », mais tu n'as rien réglé. La machine l'a réglé pour toi, et toi, de ton côté, tu as juste regardé ce
qu'elle a fait.

> **Eva :** C'est quelque chose qui revient beaucoup chez nous, mais aussi ailleurs. L'impression d'être devenu un
> simple exécutant, de ne plus coder, juste de faire de la lecture et de dire « non, c'est pas bon » ou « oui, ok ».

Je suis au Québec, mais ça doit être pareil chez vous : il y a beaucoup de frustration dans les agences du fait qu'on ne
fait plus vraiment notre métier. On laisse la machine le faire à notre place, alors que le plaisir qu'on avait en
choisissant ce métier, c'était justement de développer, de créer, d'imaginer. C'est dommage et il y en a plein qui
abandonnent le métier parce qu'ils n'ont plus aucun plaisir à le faire. On perd des gens qu'on n'aurait normalement pas
perdus.

> **Eva :** Les entreprises qui prennent vraiment le virage de l'IA prennent aussi le risque de perdre des gens qui ne
> se retrouvent plus ni dans le projet, ni dans les valeurs que ça représente.

Oui, et on le voit dans tous les domaines. Sans compter que les entreprises licencient énormément : supposément parce
que « l'IA fait tout ».

## Sa position

### « La machine ne sait pas vraiment comment construire une interface »

> Notre designeuse nous a remonté la même inquiétude, côté UI et UX. Pour elle, l'IA fait l'affaire pour tester
> rapidement quelques parcours. Mais sur la partie interface, elle craint de perdre en créativité si elle ne fait pas
> elle-même ses maquettes, et de ne plus trouver de sens dans un métier justement très créatif.

> **Eva :** Comment tu vois ça, toi, sur les interfaces ?

Surtout pour l'UI et l'UX, je trouve important que ce soit l'humain qui garde la main. Parce que, comme tu disais, tous
les modèles se ressemblent. Ce n'est pas vrai que la machine sait comment construire une interface. Ce ne sont que de
gros algorithmes, qui vont par probabilité. Comme ils ont analysé beaucoup d'interfaces, toutes les interfaces vont se
ressembler et reprendre le même concept. Mais l'interface qu'elle te produit n'est pas forcément performante. L'humain
sait très bien que dans tel cas, l'usager va être gagnant en mettant le bouton à cet endroit-là plutôt qu'à cet
endroit-là. L'IA, elle, ne le saura pas ; elle ira selon la plus grande probabilité.

C'est dommage. Quand on regarde les designs récents, tout se ressemble. On dirait qu'ils sont tous faits sur le même
moule. Ils ont juste changé la palette de couleurs, et encore, elle n'est pas toujours bonne.

Dans ce domaine-là, moins on utilise l'IA, plus on a un produit de qualité pour les usagers. Mais on dirait que
l'industrie ne s'en rend plus compte. Ça prend plus de temps, oui, sauf qu'on en retire des bénéfices : moins on prend
de temps, moins on en a.

> **Eva :** Personnellement, quand j'identifie qu'un site a été fait par de l'IA, ça ne me donne pas tant confiance ni
> envie d'aller vers eux.

Oui. Et puis tous ceux qui développent des interfaces le font désormais pour les agents. Le web a toujours fonctionné
sans agent et on n'a jamais eu de problème. Je pense que si on continue à faire du web avec de la créativité, les agents
sauront s'adapter. Les entreprises, de toute façon, n'auront pas intérêt à ne pas adapter la technologie pour pouvoir
lire les sites web. Donc pourquoi perdre en créativité, puis en humanité, simplement pour s'adapter à une technologie ?
Je trouve ça un peu dommage.

> **Maxime :** Il y a 10-15 ans, on tenait un peu le même discours face aux premiers frameworks CSS. Finalement, ils ont
> participé à faire émerger des standards, à partir desquels le web s'est structuré et professionnalisé, sans pour
> autant toujours uniformiser les créations. Est-ce qu'aujourd'hui la solution, c'est vraiment de se passer de l'IA pour
> ces aspects, ou est-ce que ce n'est pas de trouver comment encoder sa personnalité et sa créativité dans les process
> qu'on lance avec elle ?

Je pense que c'est un peu un mix des deux. Le problème je crois, c'est qu'on est dans une zone tampon, où les gens ne
font pas la deuxième partie, celle de mixer la créativité humaine avec l'IA.
On est dans une période où les gens délèguent trop, probablement parce que c'est une nouvelle technologie et qu'il
faudra encore plusieurs années avant que la hype descende.

Au début, les sites se ressemblaient presque tous. À un moment donné, la créativité et les nouveaux outils ont embarqué,
puis on a eu quelque chose de plus personnalisé. Avec l'IA, on en revient au même. Mais dans trois ou quatre ans,
peut-être un peu moins, je pense que les gens vont comprendre que sans le côté humain, le web perd de son intérêt. On va
revenir aux fondamentaux : la base, la fondation, on va la faire faire par l'IA, puis tout ce qui est le process humain,
la spécificité, va revenir dans la balance.

C'est la même chose en marketing. Des agences s'en sortent très bien parce qu'elles continuent à faire réfléchir des
humains. D'autres ont fleuri dans la dernière année, où tout est fait par l'IA, et ça se voit dans les concepts : les
affiches se ressemblent, il n'y a pas de vie, on n'est pas touché par le message.

### « Au début, ils me détestent. Mais au final, ils me remercient »

> L'IA rebat les cartes et change en profondeur notre métier. Son « de l'artisan du code à **l'architecte de
> l'intention** », dans
> [« La fin du code : Que reste-t-il quand le logiciel ne vaut plus rien ? »](https://martindufresne.substack.com/p/la-fin-du-code-que-reste-t-il-quand),
> résonne fort chez nous : la part de temps passé sur la production de code recule au profit de la conception et des
> ateliers, et nous passons progressivement de 80 % de production vers 80 % de conception. L'expertise technique pure
> n'est plus le premier critère de valeur. Pour autant, sans conserver le même attachement à la technique que ce qu'un
> senior pouvait avoir auparavant, un junior doit continuer à maîtriser et comprendre ce qu'il produit.

> **Maxime :** On se pose beaucoup de questions quant à l'apprentissage des juniors. Comment tu arrives à transmettre le
> fait qu'il faut continuer à apprendre ?

Pour les juniors à qui j'enseigne, je mets beaucoup d'emphase sur la compréhension fine des problèmes qu'ils ont à
résoudre, donc moins sur le code. Le code, la machine va toujours le produire plus rapidement ; ce qui compte pour un
junior, c'est de comprendre les problèmes qu'il rencontre.

Souvent, je leur donne du code qui ne fonctionne pas, et je les force à se passer de l'IA comme d'Internet pour
comprendre le problème. Je ne veux pas nécessairement qu'ils le résolvent : je veux qu'ils réfléchissent à pourquoi j'ai
ce problème-là, puis ce seraient quoi les pistes de solution pour le résoudre. Au début ils détestent. Ils me détestent.
Mais au final, ils me remercient, parce qu'à force, ils finissent par comprendre l'architecture du produit.

Ceux qui s'en sortiront le mieux seront ceux qui comprennent la problématique du produit plutôt que le détail de tout le
code produit. C'est vers là que je dirige les juniors. Est-ce que c'est la bonne voie ? Je ne sais pas.

Je donne des cours dans tous les domaines du web, même en UI et UX. J'ai été diplômé et ai commencé comme graphiste et
intégrateur. Pour les visuels, je leur donne deux ou trois images, et ils doivent me construire trois concepts
d'interface à partir de là, sans utiliser l'IA. Et je me rends compte que les gens ne sont comme plus capables, on a
comme perdu cette créativité-là.

Pour les développeurs, c'est la même chose. Tu prends un junior, tu lui enlèves Internet et l'IA, puis tu lui dis
« résous un problème ». Tu le mets devant un mur blanc : il n'est plus capable de parler, plus capable de réfléchir. Au
final, il s'agit de forcer les gens à réutiliser leur cerveau.

J'évalue rarement mes étudiants sur le fait qu'ils produisent le meilleur code, ou même du code fonctionnel. Je les
évalue vraiment sur la réflexion qu'ils ont par rapport à l'interface ou par rapport au code. Ça fait une grosse
différence : ils ont moins peur, ils se permettent plus de créativité, plus de folie, en sachant que ce n'est pas grave
s'ils font des erreurs. Au moins, ils ont réfléchi. Et tranquillement, tu vois la qualité augmenter, puis les
compétences aussi.

Un junior qui arrive sur le marché aujourd'hui a plein de contraintes, plein de défis et plein de stress. Avant, on
arrivait dans une boîte, on voyait les seniors ; c'était nos mentors et notre objectif. Aujourd'hui, il faut combattre
l'IA, il faut combattre les seniors… Finalement, ils deviennent tellement stressés qu'ils ne savent plus trop où aller.

À partir du moment où tu as du plaisir à faire quelque chose, je pense que la créativité, puis la facilité d'apprendre,
puis de pousser tes capacités, revient. C'est vers là qu'il faut pousser les juniors, peu importe le domaine. En tout
cas, c'est ma vision de la chose.

### « Le projet écope, tout le monde écope »

> **Eva :** Avec l'IA, mon rôle de cheffe de projet est devenu fou. Les développeurs font les tickets de spécification
> et gèrent ce que moi je gérais en amont, et j'arrive de moins en moins à rentrer dans les projets. La marche pour
> entrer dans un projet dont tu n'as défini ni les specs ni les tickets est vraiment de plus en plus importante. Est-ce
> que tu as déjà eu l'occasion d'en discuter avec d'autres chefs de projet, ou est-ce que tu as ta propre vision
> là-dessus ?

Comme je suis indépendant, je travaille sur plusieurs types de projets, donc j'embarque dans beaucoup d'équipes. Ce que
je remarque, c'est que les chargés de projet qui réussissent le mieux à entrer dans un projet s'assoient avec tous les
membres de l'équipe et se font expliquer le projet étape par étape, du début à la fin. Ça peut être long : j'ai déjà vu
que ça prenait la semaine au complet.

Sauf que c'est super important, parce que tu comprends toutes les étapes de création qui ont mené là où ils en sont
rendus. Ce n'est pas une perte de temps, c'est un gain de temps : en sachant le pourquoi de chaque décision, on a un
portrait global du projet, et le chargé de projet peut beaucoup mieux outiller ses équipes. Lui aussi est moins perdu,
il a plus de plaisir, parce que c'est difficile de courir sans arrêt après l'information. Sans cela, le projet se
termine et la personne est encore en train d'essayer de comprendre pourquoi.

> **Eva :** Ça veut dire que le chef de projet n'intervenait pas forcément au début du projet, mais quand le projet
> avait déjà avancé ?

Oui, absolument. Sur les projets où j'ai travaillé au Québec, les devs sont souvent à la fin, et j'imagine que c'est
pareil un peu partout : tout le process créatif est fait indépendamment des développeurs. Le chargé de projet arrive
quasiment en même temps qu'eux, et c'est plate (_c'est ennuyeux_), parce que les devs découvrent ce qu'il va y avoir
dans l'interface. Finalement, tout le monde y perd.

En faisant une rencontre dès l'arrivée du chargé de projet, les devs sont aussi contents que les créatifs, parce que
tout le monde se met sur la même longueur d'onde. C'est difficile d'arriver dans un projet et d'apprendre les décisions
déjà prises.

> **Eva :** Dans notre cas, on avait pour habitude justement d'embarquer tout le monde dès la phase de conception. Mais
> aujourd'hui, l'IA a changé la donne en avant-vente : elle capte et structure les besoins plus vite et plus finement
> que jamais. Résultat, on a déjà fait tout un backlog, validé les grosses fonctionnalités, et c'est une seule personne
> qui a toutes les connaissances du projet. Au moment de le passer à l'équipe, il y a quelque chose qui ne fonctionne
> pas.

Oui, et quand une seule personne détient toute la connaissance du projet, ça fait un petit peu l'effet du téléphone
arabe. On y perd, parce qu'elle, elle comprenait au moment où elle l'a entendu. Mais quand vient le temps de
transmettre, finalement, chacun a un petit bout, mais personne n'a l'ensemble. Ce n'est pas facile pour l'équipe. Il
faudrait revoir ce fonctionnement-là, parce qu'au final le projet écope, tout le monde écope. Et ça met un stress
inutile sur une équipe qui doit rendre un projet pensé sans elle.

## Sa pratique

### « Lorsqu'on écrit, on réfléchit »

> [« Plus de gens devraient écrire »](https://martindufresne.substack.com/p/plus-de-gens-devraient-ecrire) nous
> interpelle particulièrement, à l'ère des contenus générés en masse, du FOMO et du doom-scrolling. Il y défend en fait
> l'écriture comme exercice de pensée plutôt que comme moyen de se faire lire.

> **Maxime :** Pourquoi tu plébiscites encore autant l'écriture aujourd'hui, et qu'est-ce que tu en retires
> concrètement ?

Principalement parce que lorsqu'on écrit, on réfléchit. Ça nous force à réfléchir à ce qu'on dit. Aujourd'hui, les gens
écrivent moins, et vous allez le remarquer : quand on écrit moins, on a tendance à être plus impulsif, on dit quelque
chose, puis après on réfléchit. Alors qu'écrire prend plus de temps.

Je suis encore de la vieille école. J'écris beaucoup à la main, sur papier, surtout quand je développe des projets. Ce
n'est pas rare que j'écrive tout le projet à la main, graphiques compris. Et souvent, pendant que je le fais, je me
rends compte de ce qui n'est pas logique dans le projet. Je peux donc y revenir, et réfléchir. Quand je démarre avec un
plan sur papier, c'est beaucoup plus clair, parce que j'ai vraiment pris le temps.

Les gens lisent de moins en moins, en tout cas au Québec, je ne sais pas en Europe. Et je trouve ça mauvais : lire ou
écrire force une réflexion qu'on n'a pas nécessairement en automatisant tout.

Pour ma newsletter, je ne fais aucune publicité. J'ai une centaine d'abonnés et je ne pousse pas plus que ça : si les
gens accrochent, ils s'inscrivent, c'est correct. Mais je le fais parce que ça me permet de réfléchir et de parler des
sujets qui me tiennent à cœur. Si ça peut toucher des gens, tant mieux. Mais j'écris d'abord pour moi.

Tout le monde aurait intérêt à écrire. Pas nécessairement une newsletter, mais à prendre le temps de coucher sur papier
ce qu'on a à dire. Et en développement surtout, j'aime beaucoup plus travailler sur papier qu'à l'écran, parce qu'à
l'écran je réfléchis moins, c'est devenu un automatisme.

> **Maxime :** Et tu écris tout à la main, tu ne délègues rien à l'IA ?

Non, absolument rien. L'idée de base de la newsletter, c'était de prendre 45 minutes le matin et de l'écrire dans ce
temps-là. Dès que les 45 minutes étaient écoulées, je l'envoyais. Aujourd'hui les articles sont plus réfléchis, ça peut
prendre 5 à 6 heures. Mais je ne délègue rien. Souvent, j'envoie l'article sans en être pleinement satisfait : je me dis
j'aurais dû mettre ça, j'aurais dû mettre ça. Puis bon, pas grave, je l'envoie.

### « Tout est prétexte à consommer de l'IA »

> L'usage de l'IA a une vraie répercussion sur l'environnement, et comme nous l'utilisons au quotidien, notre empreinte
> a fortement augmenté.

> **Eva :** Est-ce que tu as déjà réfléchi à cette question, et est-ce que tu as des pistes pour limiter cet impact ?

Je n'utilise pas de gros modèles de langage, juste des petits modèles locaux. Ça consomme quand même, mais je trouve que
ça consomme moins. Les gros modèles généralistes consomment inutilement pour les besoins de monsieur et madame
Tout-le-Monde.

Prenez les Google Overviews, qui viennent d'arriver en Europe : on pose une question à Google, et Google nous donne un
résumé généré par l'IA. Est-ce que c'était vraiment nécessaire ? Aujourd'hui, tout est prétexte à consommer de l'IA, et
avec les impacts qui viennent avec.

J'aime beaucoup l'approche de Firefox, qui permet de désactiver entièrement l'IA du navigateur. Avec Chrome, on n'a plus
cette option-là. C'est tu l'utilises ou tu l'utilises.

À terme on va foncer dans le mur. Les grandes compagnies disent « oui, mais on fait des efforts ». Elles n'en font
aucun, parce que financièrement, ça ne serait pas à leur avantage. Je préfère donc les petits modèles locaux : les
réponses sont moins pertinentes qu'avec un Claude, mais j'ai ce dont j'ai besoin. Il va falloir une législation pour
encadrer tout ça, mais vu la vitesse des gouvernements, on va attendre longtemps.

### « Je n'écris aucun article dont je suis 100 % satisfait »

> L'idée exprimée dans [« Soixante-dix pour cent »](https://martindufresne.substack.com/p/soixante-dix-pour-cent), viser
> 70 % plutôt que la perfection parce que s'acharner sur les derniers pourcents coûte un temps fou pour un gain
> marginal, nous parle beaucoup mais reste difficile à appliquer pour certains.

> **Maxime :** J'avais tendance à voir mon perfectionnisme et mon acharnement comme une force, la source d'une certaine
> rigueur. Aujourd'hui j'y vois surtout un piège : vouloir faire 100 % plutôt que de me satisfaire de moins, c'est
> souvent une façon de ne pas trancher. C'est parfois un frein pour me lancer ou me sentir légitime, par peur de ne pas
> répondre entièrement aux attentes.
> D'où te vient cette règle, et est-ce que tu arrives vraiment à l'appliquer aujourd'hui ?

Oui, j'arrive à l'appliquer aujourd'hui. Moi aussi, j'ai été très, très perfectionniste : je visais toujours le 100 %.
Sauf que je me suis rendu compte que peu importe le produit, j'avais beau dire « là, c'est terminé », dès la mise en
production je devais retravailler. Il y avait toujours quelque chose qui manquait. Donc je n'arrivais jamais, de toute
façon, au 100 %.

En appliquant **la règle des 70 %**, je me dis : pourvu que les fonctionnalités essentielles y soient, je sais que je
vais retravailler le projet de toute façon. Tout ce que je voulais implémenter, j'y arriverai, mais sur le long terme.

J'y vais donc au 70 %, pourvu que l'application soit utilisable et solide. Je la lance en prod, et j'ajoute les petites
fonctionnalités ensuite, tranquillement. Ça m'a libéré énormément de stress. Et ça m'a permis d'avancer beaucoup plus
vite sur mes travaux.

> **Maxime :** C'est valable pour du développement logiciel, mais pour l'écriture de tes articles, comment tu définis
> cette limite ? Tu as parlé des 45 minutes d'écriture ; tu en as d'autres, des règles comme ça ?

Je n'ai pas tant de règles que ça. Je m'en crée pour voir si elles tiennent. Les 45 minutes, ça a fonctionné pendant les
10 à 12 premiers numéros, mais je me trouvais trop limité dans ce que je voulais expliquer, donc j'ai fait sauter la
règle.
J'en essaie plein d'autres, à droite et à gauche, autant en développement qu'en enseignement ou en écriture. Quand je
vois qu'une règle me convient et qu'elle fonctionne bien, je l'explique dans ma newsletter, en me disant que ça parlera
peut-être à d'autres.

Quand je décide de faire un article, je trouve d'abord le sujet, puis je couche les points principaux que je veux
dedans. Je les traite tous, puis une fois que j'ai écrit la conclusion, je sais que l'article n'est pas complet. Mais je
me dis : il est suffisamment complet pour que les gens en tirent des bénéfices, donc je le pousse quand même. Je
n'écris aucun article dont je suis 100 % satisfait.

### « Je reste le petit gars acharné qui s'amuse à réinventer le monde »

> Dans
> [« L'idée de devoir réfléchir intensément me manque »](https://martindufresne.substack.com/p/lidee-de-devoir-reflechir-intensement),
> Martin décrit deux traits qui cohabitent en lui : le Bâtisseur, celui qui veut créer, livrer avec pragmatisme, et le
> Penseur, qui a besoin de ruminer un problème difficile pendant des jours, parfois des semaines. Lors de ses études, il
> était de ceux qui s'entêtaient ainsi, là où la plupart allaient chercher de l'aide ou abandonnaient. Il considère
> cette capacité de réflexion prolongée comme son atout majeur. Longtemps, le développement a nourri ces deux traits.
> Aujourd'hui, l'IA comble le Bâtisseur, mais laisse le Penseur à sec…

> **Maxime :** Je ne suis pas sûr de me considérer comme un penseur, mais plutôt comme un acharné : m'accrocher à une
> tâche et comprendre de bout en bout, trouver une solution élégante même à un problème mineur, m'investir là où
> d'autres n'auraient pas cherché à s'attarder. J'ai longtemps trouvé du plaisir là-dedans, dans l'open source
> notamment. Mais après un an à ne produire de code qu'avec l'assistance d'une IA, j'ai un peu perdu cet esprit. La
> force que j'avais à m'acharner n'est plus aussi récompensée, et l'expertise est diluée et aujourd'hui accessible à
> chacun : tout le monde peut produire à toute vitesse une solution ad-hoc qui fonctionne.  
> Acharnement et « pensée intense », pour toi c'est différent, ou ça se rejoint ?

Oui, moi aussi, j'ai été longtemps à m'acharner. Je m'acharne encore, d'ailleurs. Mais aujourd'hui, j'ai tendance à me
dire : je vais créer une fonction qu'on retrouve à peu près partout, mais y ajouter un petit peu ma touche. Des fois
c'est très subtil, un effet en survol sur une icône. J'ai du plaisir à créer ce petit effet-là, unique au produit. Dans
le fond, ça m'a pris 15 minutes. Mais c'est 15 minutes où j'ai eu vraiment du plaisir. C'est ma façon à moi d'aller
chercher ce petit côté magique.

Je reste le petit gars acharné qui s'amuse à essayer de réinventer le monde sur Internet à chaque jour. Je suis encore
un grand enfant : je navigue, je vois une icône avec un petit effet et je suis là : « waouuuuh ». C'est ça qui est
plaisant dans notre domaine. On a cette compétence, cette connaissance-là, qui n'est pas donnée à tous. Donc aussi bien
en tirer du plaisir.

Les clients ne me demandent jamais le petit effet sur l'icône ou sur le bouton, mais ils trouvent que ça ajoute un petit
côté humain à leur outil. C'est très rare qu'on me demande de le retirer.
Ce que j'aime beaucoup faire, souvent sur de gros projets, c'est ce qu'on appelle des _easter eggs_, des portions
cachées : dans certains, une combinaison de touches te donnait un petit jeu de Sudoku. On s'entend, quand on travaille
dans un tableur toute la journée, avoir de quoi se détendre sans quitter le projet, ça peut être plaisant. C'est le
genre de petites choses que j'ajoute même si ce n'est pas demandé. J'y prends du plaisir. Je suis un grand enfant.

> **Maxime :** Tu vas pouvoir t'amuser en allant sur notre site. Nous aussi, on aime bien ça, les petits easter eggs 😉

## Son regret

### « Il n'y a plus de réflexion, de fond, il n'y a plus de partage »

> **Maxime :** Tu écrivais que même le Bâtisseur en toi a du mal à revenir en arrière sans l'IA, tout en ayant un
> certain regret de l'époque d'avant. Aujourd'hui, tu en es où de ce tiraillement ?

J'en suis au même point. Je regrette beaucoup l'avant-IA : cette époque avait quelque chose de magique, quelque chose
d'humain. Et c'était le fun, parce que c'était l'époque où on échangeait vraiment. Ça pouvait être quelque chose de
très, très petit, mais tout le monde se partageait la connaissance.

Aujourd'hui, il n'y a plus vraiment de partage de connaissances, parce que les gens publient à tout va. Des choses qui
n'ont plus vraiment de saveur. Avant l'IA, je trouve que les découvertes avaient une petite saveur. Tout le monde peut
désormais tout faire rapidement, sans réfléchir, et ça, ça manque. Il n'y a plus de réflexion, de fond, il n'y a plus de
communication, il n'y a plus de partage.

## Pour finir

> **Eva :** Est-ce que tu as un projet en cours que tu aurais envie de partager avec nous ?

À part la morphogénèse logicielle, dont je continue les travaux, c'est à peu près le seul projet d'à côté que je mène.
Sinon je travaille sur beaucoup de formations, dont l'informatique quantique, qui démarre tranquillement. En entreprise,
ça va être intéressant, surtout côté sécurité, et même côté intelligence artificielle, parce qu'on peut maintenant
développer de l'IA avec un ordinateur quantique. J'y apporterai un côté un peu plus technique, tout en le gardant
extrêmement vulgarisé, pour que n'importe qui puisse le comprendre.

Quand mon petit système d'exploitation fonctionnera comme je le souhaite, je partagerai sûrement des détails techniques,
avec un lien pour que les gens puissent l'essayer. J'espère pouvoir le terminer d'ici la fin de l'automne.

Puis si jamais vous avez des idées de sujets… Ce que je dis souvent aux gens : si vous voulez que je réfléchisse à
quelque chose, que je l'aborde dans la newsletter, écrivez-le-moi. J'y réfléchis, puis à un moment donné une idée me
vient, et je le traite. Je n'ai pas tout le temps des sujets sur lesquels écrire. C'est même le plus difficile avec la
newsletter : en trouver chaque semaine sans être redondant.

> Nous remercions chaleureusement Martin pour son temps, sa franchise et sa bonne humeur. Nous avons grandement apprécié
> cet échange, très enrichissant. Ce qui devait être une interview d'une heure est devenu une conversation à trois, où
> nous avons autant parlé de nos propres impasses que des siennes.
>
> Il nous a laissé une invitation, vous l'avez compris, mais nous vous la retransmettons : si vous avez des idées de
> sujets pour sa newsletter, écrivez-lui !

### Pour suivre Martin

- [Son site](https://martindufresne.com/)
- [Son blog](https://martindufresne.com/blog/)
- [Sa newsletter sur Substack](https://substack.com/@martindufresne)
- [Son compte Bluesky](https://bsky.app/profile/mdufresne.bsky.social)

### Les voix qu'il nous recommande

> Nous avons terminé en lui demandant qui gagnerait à être suivi. Des personnes comme lui, qui publient. Voici une
> petite liste :

- **Paul Gauthier**, créateur d'Aider, pour suivre « l'évolution du pair programming en ligne de commande et la façon
  dont les LLM peuvent s'intégrer dans le flux du développeur » : [LinkedIn](https://www.linkedin.com/in/paulgauthier/)
  et [X](https://x.com/paulgauthier).
- **Ethan Mollick**, professeur aux États-Unis, qui « parle énormément de tout ce qui est de la posture à adopter face à
  l'IA, l'expérimentation » : sa newsletter [One Useful Thing](https://www.oneusefulthing.org/) et
  [X](https://x.com/emollick).
- **Clément Delangue**, de Hugging Face : « Parle beaucoup du mouvement open source, de la souveraineté et des
  modèles. » Son [LinkedIn](https://www.linkedin.com/in/clementdelangue/).
