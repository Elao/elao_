---
type:               "post"
title:              "Responsive Web Design"
date:               "2012-07-31"
lastModified:       ~

description:        "Responsive Web Design"

thumbnail:          "images/posts/thumbnails/html5.jpg"
banner:             "images/posts/headers/elao_team_front.jpg"
tags:               ["CSS", "Webdesign"]
categories:         ["Dev", "Web", "CSS", "Webdesign"]

author:    "fzannettacci"
---

Le web design responsive est né d'un constat simple, le web sort de nos bureaux pour prendre possession de nos mobiles, tablettes et télévisions. Notre conception web est ainsi contrainte à suivre cette multiplication des supports et dans certains cas, l’idée de créer différentes versions selon chaque appareil, serait simplement une perte de temps et d’énergie.<!--more-->

### Qu'est ce que le responsive webdesign ?
Le web design responsive amène une solution pratique et économique à cette situation. En effet, il permet de créer une seule et unique interface qui affiche le site sous sa meilleure forme, et ce, peu importe la taille de l’écran. La structure de la page, la taille des images et polices peuvent s’ajuster, se déplacer, s’effacer... Vous serez amener à réfléchir aux différents changements de votre charte graphique sur les différents types de supports. Le responsive illustré : <http://www.thismanslife.co.uk/projects/lab/responsiveillustration/>

### Utilisation
* Préparer l'entête du modèle XHTML
* Pour commencer, il faut fixer le viewport en insérant la balise meta suivante :

```html

<meta name="viewport" content="width=device-width, initial-scale=1.0;" />
```


### Les 3 grands principes

#### 1. La grille flexible

Adieu grille fixe en pixels, bonjour grille flexible ou fluide en pourcentage. Chaque élément de notre maquette doit ainsi s’exprimer en terme relatifs et proportionnels à son contenant d’où la formule :  `cible / contexte = résultat`.

![contruire grid Responsive Web Design](images/posts/2012/contruire-grid.jpg)

**Gagnez du temps**

De nombreux frameworks sont disponibles sur le web pour vous faciliter cette étape un peu complexe. Et comme je n’ai pas la prétention de les avoir tous testé, je vous laisse faire votre choix parmi les plus performants : <http://medleyweb.com/resources/responsive-web-design-frameworks/>

Le framework <a title="Foundation " href="http://foundation.zurb.com/" target="_blank">Foundation</a> créé par l’agence Zurb est utilisé dans la refonte du site ELAO. Très complet, il s’apparente au framework Twitter Bootsrap embarquant un certain nombre de styles et fonctionnalités faciles à utiliser. Cependant, je trouve sa grille plus fluide (calculée entièrement en pourcentage).

#### 2. Images flexibles

Pour empêcher les images de dépasser la largeur de leur contenant et les forcer à s’ajuster :

```scss
max-width: 100%;/* pour limiter la taille à la largeur du parent */
height: auto;/* pour conserver le ratio */
width: auto;/* pour corriger un bug sur IE8  */
box-sizing: border-box;/*pour limiter à 100% de la largeur, même si des paddings ou bordures sont appliquées à l’élément */
```


En ce qui concerne les background-images :

```scss

-moz-background-size: 100%;
-webkit-background-size: 100%;
-o-background-size: 100%;
background-size: 100%;
```


**Et parce que des fois ce n’est pas si simple ...**
Parfois, la gestion des images sur un site en responsive design peut s'avérer plus complexe : mauvais redimensionnement, poids trop lourd, ... Autant de contraintes et de questions qui trouveront sûrement solutions avec ces deux outils :

* <a title="Adaptive Images" href="http://adaptive-images.com/" target="_blank"><strong>Adaptative Images</strong> </a>Basé sur PHP et Javascript, Adaptative Images détecte automatiquement la résolution du visiteur et va générer l’image à la taille optimale et recalcule son poids.
* <a href="http://www.sencha.com/products/io/" target="_blank"><strong>Sencha</strong></a> Egalement utile pour le redimensionnement d’images à la bonne taille et au bon format.

#### 3. Media Queries

Aussi douloureux que cela puisse être, il est temps de redimensionner votre fenêtre et d’identifier les différents problèmes rencontrés pour pouvoir les corriger ..

Les medias queries vont permettre cela en modifiant l’apparence d’une page HTML en fonction de la résolution d’un appareil (téléphone, console de jeux, tablettes, ordinateur, …) grâce à des conditions de largeur et/ou d’orientation.

Elles n’ont pas d’impact sur le code HTML. Ainsi, on reste dépendant de l’ordre dans lequel a été écrit le HTML. Impossible donc d’intervertir une boîte avec une autre ou d’associer dans un même ensemble deux informations séparées dans le code, à moins de tricher en CSS, mais cette technique est quasi incontrôlable sur tous les appareils… D’où la philosophie de design «Mobile First» conseillant de commencer la conception par le design mobile.

```scss

/* Exemple d’utilisation */
@media only screen and (max-width: 767px) { {   /* Smartphones */ }
@media only screen and (min-width: 768px) and (max-width: 1024px) {   /* Tablettes */ }
@media only screen and (min-width: 1024px) {   /* Desktops */ }
```


**Pour notre ami IE  : **
[Respond.js][1] permet d’utiliser les media queries même sur IE 6 à 8.

### Outils et sources

Tester son site :

*   <a href="http://screenqueri.es/" target="_blank">Screenqueri</a>
*   <a href="http://www.we-are-gurus.com/tools/responsive-design-tester.php" target="_blank">We are gurus </a>

Slider responsive :  <a href="http://nivo.dev7studios.com/" target="_blank">Nivo slider </a>

Sources  :

*   Parfait pour débuter : Responsive web design par Ethan Marcotte (Éditions Eyrolles / A book apart)
*   Rappel des bases avec une <a href="http://designwoop.com/2012/03/15-detailed-responsive-web-design-tutorials/" target="_blank">liste des tutos</a> par DesignWoop

 [1]: https://github.com/scottjehl/Respond
