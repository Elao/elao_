---
type:               "post"
title:              "Maintenabilité et performance avec Sass et Compass"
date:               "2013-04-09"
publishdate:        "2013-04-09"
draft:              false
slug:               "maintenabilite-et-performance-avec-sass-et-compass"
description:        "Maintenabilité et performance avec Sass et Compass"

thumbnail:          "/images/posts/thumbnails/sass.png"
header_img:         "/images/posts/headers/elao_team_front.jpg"
tags:               ["CSS", "Webdesign", "Développement"]
categories:         ["Dev", "Web", "CSS", "Webdesign"]

author_username:    "fzannettacci"
---

L'augmentation croissante du nombre de règles et de fichiers, le souci de la compatibilité des navigateurs, l'utilisation de CSS3, le travail en équipe, ... sont autant de facteurs qui complexifient le code CSS et peuvent le rendre hors de contrôle. <!--more-->L’objectif de cet article est de vous présenter les différentes fonctionnalités du préprocesseur Sass et son utilisation avec Compass qui vous aideront à gagner du temps sur vos intégrations.

### 1. Sass

#### Presentation de Sass

Sass est un préprocesseur et une extension CSS3 dont l’objectif est d’apporter à CSS la puissance des langages de développement tel que PHP, Java ou Ruby. Il s’agit de nombreux atouts comme les variables, les opérations, les sélecteurs imbriqués, les extensions de classes ou la vérification du code non négligeables pour produire un code lisible et performant dans sa maintenabilité.

#### Principales fonctionnalités :

*   **Les variables** vont permettre de stocker n'importe quelles informations (couleur, taille, texte, etc.) dans un objet que l'on déclare :

{{< highlight css >}}
$macouleur = #FFF ;
{{< /highlight >}}


*   **Les règles imbriquées et les sélecteurs avancés** vont rendre les règles CSS plus compréhensibles selon le principe DRY ( Don’t Repeat Yourself ) avec l’imbrication des règles filles ou des actions contextualisées.

##### *Exemple d’une navigation :*

HTML

{{< highlight html >}}
<nav>
    <ul>
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
</nav>
{{< /highlight >}}

SCSS

{{< highlight scss >}}
nav {
    border-bottom : 1px solid #CCC;
    ul {
        list-style-type : none;
        li{
            float : left;
            margin-right : 10px;
            &:last-child { margin-right: 0; }
        }
    }
}
.btn {
    padding: 5px;
    .red {
         background-color : #666;
         &:hover, &:active { background-color : #333;}
   }
}
{{< /highlight >}}

*   **Les mixins** vont permettre de réutiliser des morceaux de codes à n’importe quel endroit de vos feuilles de styles. Il est possible de leur donner des arguments afin de créer des fonctions complexes en utilisant qu’une seule ligne de code, très utile pour le cross browsers et les multiples variantes des propriétés CSS3.

Un mixin se definit grace à la directive `@mixin`.

{{< highlight scss >}}

@mixin mon-mixin{ 
 //instructions
}
{{< /highlight >}}


Et s’appelle avec la directive `@include`

{{< highlight scss >}}
@mixin mon-mixin{ 
     -moz-border-radius: 10px;
     -webkit-border-radius: 10px;
     border-radius: 10px;
 }

.btn {
    @include mon-mixin.
}
{{< /highlight >}}

Exemple avec arguments

{{< highlight scss >}}
@mixin belles-bordures-arrondies($arrondi) {
    -moz-border-radius: $arrondi;
    -webkit-border-radius: $arrondi;
    border-radius: $arrondi;
}
.btn {
    @include belles-bordures-arrondies(10px);
}
{{< /highlight >}}


*   **L'héritage** avec la directive `@extend` va permettre à un sélecteur d'hériter de tous les styles d'un autre sans dupliquer les propriétés CSS.

{{< highlight scss >}}
.error {
    border: 1px solid $grey-border;
    background: $grey;
}

.badError {
    @extend .error; 
    border-width: 3px;
}
{{< /highlight >}}

#### Deux extensions de fichiers possibles

Sass possède deux syntaxes :

*   Le SCSS est la plus répandue car elle reprend la syntaxe CSS. Son extension de fichiers est .scss

{{< highlight scss >}}
/* SCSS */
 section {
     margin-bottom: 1em;
     .entry {
         color: red;
     }
 }
{{< /highlight >}}


*   Le SASS s'écrit sans élément de ponctuation. L’indentation marque l’imbrication des propriétés, des classes, etc ...; et le saut de ligne remplace le point-virgule.

{{< highlight scss >}}

/* SASS */
 section
    margin-bottom: 1em
    .entry
        color: red
{{< /highlight >}}


#### Installation

SASS nécessite le langage `RUBY` pour fonctionner. Assurez vous d’avoir un environnement Ruby à jour ou installer le. Puis installer SASS, avec la commande

{{< highlight bash >}}

gem install sass
{{< /highlight >}}


#### Utilisation

Vos fichiers .scss ou .sass sont créés et prêts à être compilés en `.css`.

Plusieurs façons :

*   en ligne de commande : avec la commande `sass --watch nomdufichier.scss`

*   intégré à un framework comme Compass.

### 2. Compass

#### Presentation

Compass est un framework pour Sass qui fournit une collection d'outils et de mixins déjà programmés. Il suffit d'importer Compass dans vos fichiers .scss pour y avoir accès et les utiliser dans vos projets :

{{< highlight scss >}}

@import "compass";
{{< /highlight >}}


#### Fonctionnalités

*   Propriétés CSS3 et préfixe constructeur

Si les nouveautés apportées par CSS3 sont assez fantastiques et prometteuses, leur mise en place cross-browser est plus complexe. Elle nécessite en effet des préfixes constructeur différents selon chaque navigateur. Compass prévoit des mixins pour un certain nombre de propriétés CSS3.

**Box-shadow**

{{< highlight scss >}}

@include box-shadow(#000 1px 1px 4px);
{{< /highlight >}}


**Border-radius**

{{< highlight scss >}}

@include border-radius(5px);
{{< /highlight >}}


**Background gradients**

{{< highlight scss >}}

@include background-image(linear-gradient(white, #aaaaaa));
{{< /highlight >}}


**Transitions**

{{< highlight scss >}}

@include transition(all 0.2s linear);
{{< /highlight >}}


**Sprites**

Et pour le bonheur de tout intégrateur, Compass est capable de générer et d’optimiser la création de sprites.

Dans un premier temps, il suffit de regrouper toutes les images dans un dossier. Par exemple, nous plaçons, quatre icones dans le dossier "icons" : home.png, about.png, contact.png, logout.png.

Dans un second temps, dans votre fichier scss, il faut importer les images puis générer le sprite.

{{< highlight scss >}}

@import "<strong>icons</strong>/*.png";
@include all-<strong>icons</strong>-sprites;
$arrow-sprite-dimensions: true; /*Calculer automatiquement les dimensions */
{{< /highlight >}}


Le sprite généré va inclure autant de classes qu'il y a d'images. Chaque élément aura comme classe "nomdudossierparent-nomdufichier". Dans l'exemple, la première icone aura ainsi comme classe "icons-home"

CSS généré :

{{< highlight scss >}}

.icons-home, .icons-about, .icons-contact, .icons-logout {
  background: url('../img/icon-s8021aff651.png') no-repeat;
}
.icons-home {
  background-position: 0 -72px;
 }
{{< /highlight >}}


#### Installation

Un projet Compass se crée en ligne de commande. Si le terminal vous rebute un peu, plusieurs outils tels quel Compass.app, CodeKit ou Scout ont une interface dédiée pour vous aider dans cette tâche.

{{< highlight scss >}}

gem install compass
{{< /highlight >}}


#### Création et compilation d'un projet

{{< highlight scss >}}

compass create nomduprojet
{{< /highlight >}}

Vous disposez alors d'un exemple possible d'arborescence de projet avec un ensemble de répertoires et fichiers.

*   un fichier de configuration config.rb
*   le répertoire sass des sources Sass , avec 3 exemples de feuilles de styles :
    *   screen.scss pour l’affichage écran ;
    *   print.scss pour l’impression ;
    *   ie.scss pour vos surcharges à destinations d’Internet Explorer.
*   un répertoire stylesheets qui reçoit les fichiers sources compilées.

Vous travaillerez désormais avec les fichiers.scss contenus dans le répertoire sass. Pour que Compass détecte chaque modification dans votre fichier `.scss` et le compile en `.css` dans le répertoire stylesheets, lancez la commande :

```
compass watch
```

### Conclusion

Même si cet article ne présente que les grandes lignes de l'utilisation de Sass et Compass, j'espère avoir réussi à vous montrer sa puissance et sa valeur ajoutée apportée. Il y a encore de nombreuses autres fonctionnalités et mixins à découvrir pour gagner du temps et en productivité dans vos intégrations.

Quelques ressources pour vous aider à sauter le pas :

*   les documentations de <a title="Sass Doc" href="http://sass-lang.com/" target="_blank">Sass</a> et de <a title="Compass Doc" href="http://compass-style.org/" target="_blank">Compass</a>,
*   Livre : <a href="http://www.css-maintenables.fr/" target="_blank">CSS maintenables avec Sass et Compass</a> de Kaelig Deloumeau-Prigent
*   <a href="http://pioupioum.fr/compass-sauvez-integrateur" target="_blank">Compass, Sauvez l'intégrateur</a> (prise en main, tutoriels)
*   <a href="http://labs.excilys.com/2012/06/14/a-la-decouverte-de-sass-syntactically-awesome-stylesheets/" target="_blank">A la découverte de Sass </a>
*   <a href="http://www.grafikart.fr/tutoriels/html-css/framework-css-compass-140" target="_blank">Tutoriel vidéo HTML-CSS : Framework CSS Compass</a>
*   <a href="http://www.netmagazine.com/tutorials/boost-sass-compass-efficiency" target="_blank">Augmentez l'efficacité de Sass & Compass </a>(en anglais)
