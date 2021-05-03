---
type:               "post"
title:              "Retour sur le Best Of Web 2016"
date:               "2016-06-17"
lastModified:       ~

description:        "La seconde édition du Best Of Web s'est tenue le Vendredi 10 Juin 2016 à Paris, et a rassemblé le meilleur des meetups de l'année. Retour sur cet événement."

thumbnail:          "images/posts/thumbnails/best-of-web-2016.jpg"
banner:             "images/posts/headers/best-of-web-2016.jpg"
tags:               ["Javascript", "Web","conference"]
categories:         ["Actualité", "Web", "conference"]

author:    "mcolin"
---

La seconde édition du Best Of Web s'est tenue à Paris le vendredi 10 juin 2016. Comme l'année dernière c'est la salle de la Grande Crypte de Paris qui a hébergé l'événement. Le principe ne change pas, pendant une journée l'événement propose de revivre les meilleurs meetups parisiens orientés web.
<!--more-->
L'événement est plutôt axé front, ça parle donc de Javascript, de CSS, de webperf, ... Le format de l'année précédente a été conservé, c'est-à-dire une seule track pour ne rien manquer.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">On est à <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a> n&#39;hésitez pas à nous pinger pour que l&#39;on se rencontre <a href="https://t.co/KAoWNerGSU">https://t.co/KAoWNerGSU</a> <a href="https://t.co/qWNrKiPZ88">pic.twitter.com/qWNrKiPZ88</a></p>&mdash; Elao (@Elao) <a href="https://twitter.com/Elao/status/741168106723258368">10 juin 2016</a></blockquote>

# Frameworks temps réel

L'un des sujets les plus souvent abordés était celui des frameworks temps réel. Backbone, Angular, Meteor ou encore React pour les plus trendy, ces frameworks révolutionnent depuis quelques années le développement d'applications web front. A travers plusieurs talks nous avons eu un bon aperçu des différentes solutions, leurs points forts et leurs faiblesses, lesquelles utiliser et pourquoi.

Bien qu'il ne soit pas le plus utilisé, [React.js](https://facebook.github.io/react/) semble être le grand favori cette année.

Mention spéciale au format sympa de la conf tout en live coding de [Gerard Sans](https://twitter.com/gerardsans).

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Conf au format sympa (love coding + todolist) sur RxJS à <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a> by <a href="https://twitter.com/gerardsans">@gerardsans</a> <a href="https://t.co/vxBFVRLF1H">pic.twitter.com/vxBFVRLF1H</a></p>&mdash; Richard HANNA (@richardhanna) <a href="https://twitter.com/richardhanna/status/741246253913284609">10 juin 2016</a></blockquote>

# WebPerf

L'autre sujet qui tenait une bonne place dans ce Best Of Web 2016 concernait les performances. Effectivement avec le développement des applications front de plus en plus lourdes et l'arrivée de fonctionnalités permettant des opérations de plus en plus complexes (transformation, animation, 3D, ...) ainsi que l'utilisation croissante d'API, la question de la performance est devenu cruciale. Les utilisateurs, habitués à la fluidité des applications natives, s'attendent à la même réactivité de la part du web.

[Freddy Harris](https://twitter.com/harrisfreddy) a longuement parlé dans son talk des performances dans l'animation et nous donne de nombreuses astuces sur le fonctionnement des animations dans les navigateurs.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Astuce : La propriété <a href="https://twitter.com/hashtag/css?src=hash">#css</a> will-change permet d&#39;indiquer au browser que l&#39;élément sera animé <a href="https://twitter.com/hashtag/gpu?src=hash">#gpu</a> <a href="https://twitter.com/hashtag/webperf?src=hash">#webperf</a> <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a></p>&mdash; Maxime COLIN (@colin_maxime) <a href="https://twitter.com/colin_maxime/status/741179119833079808">10 juin 2016</a></blockquote>

Ensuite [Audrey Neveu](https://twitter.com/audrey_neveu) nous explique comment fonctionne le temps de réaction chez un utilisateur. Le temps de rendu de votre page ne doit pas excéder celui-ci au risque de perdre l'utilisateur.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Réaction: 100/500ms, interaction: 100/500ms. 1s de chargement = 👍, 4s = perte de patience, &gt;4s = utilisateur perdu <a href="https://twitter.com/hashtag/webperf?src=hash">#webperf</a> <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a></p>&mdash; Maxime COLIN (@colin_maxime) <a href="https://twitter.com/colin_maxime/status/741185758317424640">10 juin 2016</a></blockquote>

Elle nous présente également des cas concrets ou une baisse de reactivité a pour conséquence une perte de trafic ou pire une perte de vente.

<table style="width: 500px;margin: auto;text-align: center;">
	<thead>
		<tr style="border-bottom:2px solid #ccc;">
			<th>Site</th>
			<th>Latence</th>
			<th>Conséquence</th>
		</tr>
	</thead>
	<tbody>
		<tr style="border-bottom:1px solid #ccc;">
			<td>Amazon</td>
			<td>+100ms</td>
			<td>-1% de vente</td>
		</tr>
		<tr style="border-bottom:1px solid #ccc;">
			<td>Google</td>
			<td>+500ms</td>
			<td>-20% de traffic</td>
		</tr>
	</tbody>
</table>

Pour elle, deux technologies servent à streamer des données : WebSockets et Server-Sent Events (SSE). Si on a besoin uniquement de récupérer des données sans en envoyer, il faut mieux utiliser les SSE qui sont plus performants. L'utilisation de [JSON Patch](http://jsonpatch.com/) permet également de réduire la quantité de données qui transitent : on ne reçoit que ce qui a changé.

# L'artisanat du web

Petit coup de coeur pour le talk de [Tim Carry](https://twitter.com/pixelastic) qui nous parle d'artisanat du web. Il nous explique qu'un artisan se doit de réaliser un chef d'oeuvre au cours de sa carrière et voici le sien : [la réalisation des pays du monde uniquement avec un ```div``` et du css](https://pixelastic.github.io/css-flags/). Et vous, quel sera votre chef d'oeuvre ?

# Progressive Web Apps

Un autre sujet que j'ai trouvé très intéressant est la présentation des Progressive Web Apps par [Florian Orpelière](https://twitter.com/florpeliere). Ces applications sont des applications web pensées comme des applications natives afin d'offrir à l'utilisateur une expérience que n'offre pas une application web tout en gardant la flexibilité du web.

* Gestion des connexions lentes et le hors-ligne,
* Réception des messages push,
* Affichage des notifications,
* Synchronisation des données en arrière plan,
* Ajout à l'écran d’accueil.

Ses applications sont hébergées comme n'importe quelle application web mais disposent d'un manifest décrivant leur fonctionnement et leur configuration. Les Progressive Web Apps reposent principalement sur la technologie des Service Workers qui est actuellement en cours de standardisation et pourrait être le futur des applications web.

# OVNI

Petit OVNI à travers les différents sujets présentés, l'émulation d'une GameBoy en javascript. Au delà de la curiosité et de la coolitude de la chose, j'ai trouvé la performance très interessante pour montrer la puissance montante du language Javascript ainsi que les possibilités qu'il offre.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Emuler la <a href="https://twitter.com/hashtag/GameBoy?src=hash">#GameBoy</a> en <a href="https://twitter.com/hashtag/Javascript?src=hash">#Javascript</a> oui c&#39;est possible <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a> <a href="https://t.co/XILnVsAmVC">pic.twitter.com/XILnVsAmVC</a></p>&mdash; Maxime COLIN (@colin_maxime) <a href="https://twitter.com/colin_maxime/status/741276662185250816">10 juin 2016</a></blockquote>

# Coup de coeur

Petit coup de coeur pour l'organisation et l'ambiance qui étaient très réussi.
Les talks bien choisis et correspondaient à l'actualité de notre métier. L'accueil était chaleureux, le repas exceptionnel. Les sponsors ont bien joué le jeu et l'équipe était très sympathique.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">L&#39;inattendu mais très apprécié bar à <a href="https://twitter.com/hashtag/fromage?src=hash">#fromage</a> du <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a> 😍😋👍 <a href="https://t.co/aa7GZCmfqg">pic.twitter.com/aa7GZCmfqg</a></p>&mdash; Maxime COLIN (@colin_maxime) <a href="https://twitter.com/colin_maxime/status/741222954609053696">10 juin 2016</a></blockquote>

# Conclusion

Pour conclure, je dirais que cette seconde édition du Best Of Web était très réussies. Les talks étaient très intéressants, d'actualité et tournés vers l'avenir.
Je félicite les organisateurs pour une organisation sans faille de l'événement. Bravo et à l'année prochaine.

<blockquote class="twitter-tweet" data-lang="fr"><p lang="fr" dir="ltr">Pour revivre <a href="https://twitter.com/hashtag/bestofweb2016?src=hash">#bestofweb2016</a> retrouvez toutes les photos de l&#39;événement <a href="https://t.co/DVYfQ3lhtJ">https://t.co/DVYfQ3lhtJ</a> <a href="https://t.co/19s5fx59dl">pic.twitter.com/19s5fx59dl</a></p>&mdash; bestofweb (@bestofwebconf) <a href="https://twitter.com/bestofwebconf/status/743431195657109505">16 juin 2016</a></blockquote>

<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
