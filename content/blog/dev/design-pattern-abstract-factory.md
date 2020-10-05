---
type:           "post"
title:          "Le Design Pattern 'Abstract Factory'"
date:           "2017-04-11"
publishdate:    "2017-04-11"
draft:          false

description:    "Deuxième article d'une série consacrée aux Design Patterns. Aujourd'hui : le pattern Abstract Factory"

thumbnail:      "images/posts/thumbnails/schema.jpg"
header_img:     "images/posts/headers/header_schema.jpg"
tags:           ["Design Pattern", "Conception"]
categories:     ["Dev", "Design Pattern"]

author:    "xroldo"

---

Deuxième article d'une série consacrée aux Design Patterns. Aujourd'hui, le pattern `AbstractFactory` où il sera question de produits, de familles et de fabriques (_factories_, au pluriel s'il vous plaît).

<table width="100%">
    <tr>
        <td width="50%" align="center">
            {{< figure style="text-align:center;" src="images/posts/design-pattern/img/ingals-family.jpeg" alt="Une gentille famille américaine">}}
    <figcaption style="text-align: center;font-style: italic;">Une gentille famille américaine</figcaption>
        </td>
        <td width="50%" align="center">
            {{< figure src="images/posts/design-pattern/img/chainsaw-family.jpeg" alt="Une famille américaine">}}
<figcaption style="text-align: center;font-style: italic;">Une <strike>gentille</strike> famille américaine</figcaption>
        </td>
    </tr>
</table>

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Le livre <strong><i>Head First Design Patterns</i></strong> (<a href="/fr/dev/design-pattern-factory-method">dont j'ai déjà vanté les mérites</a>) regroupe les deux patterns <code>Factory Method</code> et <code>AbstractFactory</code> dans un même chapitre consultable en ligne et intitulé <a href="https://www.safaribooksonline.com/library/view/head-first-design/0596007124/ch04.html" target="_blank">The Factory Pattern: Baking with OO Goodness</a>. Je ne saurais trop vous encourager à le consulter !
</div>

## Classification

Le pattern `Abstract Factory` est classé dans la catégorie des __Design Patterns de création__.

## Définition

> Provide an interface for creating families of related or dependent objects without specifying their concrete classes.

C'est une très jolie définition dont je ne me lasse pas ... (Comme toute définition, elle paraît assez barbare tant que l'on n'a pas approfondi le concept, ce que je vous propose de faire dans les paragraphes qui suivent).

En résumé, le pattern `Abstract Factory` va nous permettre d'instancier des familles de produits dépendant les uns des autres sans qu'il soit nécessaire de préciser leur type concret (je ne suis pas sûr qu'on soit plus avancé ...)

## Schéma du design pattern `Abstract Factory`

<p class="text-center">
    {{< figure class="text-center" src="images/posts/design-pattern/creation-abstract-factory.png" alt="Le Design Pattern 'Abstract Factory'">}}
</p>

Ne vous laissez pas impressionner par la densité du schéma et le nombre de participants. Le pattern n'a rien d'insurmontable et peut s'avérer utile dans de nombreuses situations.

Pour l'heure, et pour y voir un peu plus clair, je vous suggère de diviser mentalement le schéma en deux :

* à gauche figurent les fabriques
* à droite les produits à instancier

Les produits appartiennent à une famille. Sur ce schéma le produit A1 et le produit B1 appartiennent à la même famille et sont donc destinés à collaborer ensemble. Le produit A2 et le produit B2 appartiennent à une autre famille et sont également conçus pour collaborer ensemble. En revanche, A1 et B2 ne peuvent pas fonctionner ensemble. Il est donc important d'instancier des produits qui sont compatibles (autrement dit, qui appartiennent à la même famille) et c'est là qu'interviennent les fabriques abstraites.

Je suis sûr qu'au cours de votre carrière de développeur vous avez été amenés à modéliser des contraintes métiers énoncées peu ou prou ainsi : "_si mon produit est une instance de A, alors c'est mon service A qui le gère ; si mon produit est une instance de B, alors c'est mon service B qui le gère_". Ne cherchez pas plus loin, c'est justement à ce genre de problématique que répond le pattern `Abstract Factory`.

## Exemple de problèmes familiaux ...

J'ai développé une extension Chrome qui permet de présenter des statistiques structurées à partir du détail d'un commit sur Github : nom du projet, nom de l'auteur, liste des fichiers concernés par les modifications, nombre de lignes supprimées, nombre de lignes ajoutées, etc.

Pour ce faire, j'ai développé une librairie qui contient deux classes qui analysent le contenu HTML d'une page Github et en extraient les données pertinentes :

- la première classe `GithubCrawler` parse le DOM (cette classe connaît les chemins XPATH qui permettent d'extraire les données brutes au format HTML)
- la seconde classe `GithubParser` sait parser les données brutes retournées par mon crawler pour en extraire les données épurées (débarrassées des tags HTML notamment) et les structurer

Mon client est aux anges et souhaite donc étendre ce fonctionnel aux projets hébergés sur Gitlab (vous la voyez arriver la nouvelle `famille` ?). Bien évidemment, la structure HTML des pages Gitlab est complètement différente des pages Github, et mon parser Github est tout à fait incapable de comprendre les données retournées par mon crawler Gitlab ... La contrainte est donc la suivante : si mon crawler est un crawler Github, alors je dois utiliser le parser Github ; si mon crawler est un crawler Gitlab, alors je dois utiliser le parser Gitlab. Et mon client ne compte pas s'arrêter là, il souhaite bien évidemment aussi gérer les pages Bitbucket ...

## Résolution de la problématique à l'aide du DP `AbstractFactory`

Vous l'aurez sans doute deviné, nous nous trouvons en présence de trois familles de produits différentes : la famille des produits Github, la famille des produits Gitlab et la famille des produits Bickbucket. Dans chacune de ces familles, on retrouve un produit `Crawler` et un produit `Parser` conçus pour collaborer ensemble.

Comment garantir que j'utilise des produits d'une même famille ? Réponse : le pattern __Abstract Factory__. Ce qui nous donne le récapitulatif suivant :

<table>
    <tr>
        <th>&nbsp;</th>
        <th>Github</th>
        <th>Gitlab</th>
        <th>BitBucket</th>
    </tr>
    <tr>
        <td>SCMCrawlerInterface:</td>
        <td colspan="3">
            <pre class="code">
public function getProjectNameHtml();
public function getSummaryHtml();
public function getCommittedFilesHtml();
            </pre>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>GithubCrawler</td>
        <td>GitlabCrawler</td>
        <td>BitBucketCrawler</td>
    </tr>
    <tr>
        <td>SCMParserInterface:</td>
        <td colspan="3">
            <pre class="code">
public function parseProjectName($projectNameHtml);
public function parseSummary($summaryHtml);
public function parseCommittedFiles($committedFilesHtml);
            </pre>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>GithubParser</td>
        <td>GitlabParser</td>
        <td>BitBucketParser</td>
    </tr>
    <tr>
        <td>SCMFactoryInterface</td>
        <td colspan="3">
            <pre class="code">
public function getCrawler();
public function getParser();
            </pre>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>GithubFactory</td>
        <td>GitlabFactory</td>
        <td>BitBucketFactory</td>
    </tr>
</table>

## Explication de la solution

* j'ai introduit une interface `SCMCrawlerInterface` qu'implémentent les crawlers de chaque famille
* j'ai également introduit une interface `SCMParserInterface` pour les parsers
* j'ai créé une fabrique (_Factory_) pour chaque famille :
    - chaque fabrique implémente l'interface `SCMFactoryInterface`
    - nous implémentons autant de fabriques concrètes que de familles
    - l'interface d'une fabrique expose autant de méthodes de création qu'il y a de produits dans une famille

Voici à titre d'exemple le code de la fabrique `GithubFactory` (je vous épargne le code des deux autres fabriques):

```php
    <?php
    class GithubFactory implements SCMFactoryInterface {
        public function getCrawler(): SCMCrawlerInterface {
            return new GithubCrawler();
        }
        public function getParser(): SCMParserInterface {
            return new GithubParser();
        }
    }
```

A présent, en fonction du contexte dans lequel nous nous trouvons (Github, Gitlab ou Bitbucket), il suffit d'instancier la bonne fabrique et d'appeler respectivement ses méthodes `getCrawler` et `getParser` pour obtenir les bons services adaptés au contexte courant. C'est au final la fabrique qui est garante de la compatibilité des produits qui collaborent.

Autre avantage de cette solution : la résolution des services à instancier selon le contexte se fait une seule fois, il suffit d'instancier la bonne fabrique (nous n'avons pas à résoudre l'instanciation du bon crawler dans un premier temps, puis du bon parser dans un second temps).

Grâce à ce pattern, l'ajout d'une nouvelle famille de produits peut également se faire assez aisément (un nouveau SCM à gérer), tout comme l'ajout d'un nouveau produit dans chaque famille (on pourrait envisager d'implémenter un Renderer spécialisé dans chaque famille, chargé de l'affichage des données obtenues, par exemple).

## Conclusion

Parce que ce sont tous deux des patterns de fabrique, on confond souvent la `Factory Method` et le pattern `Abstract Factory`. Voici donc un résumé de ces deux patterns en mettant l'accent sur ce qui les différencie :

* `Factory Method` : `new` déporté dans une méthode dédiée, un seul type d'objet retourné à la fois (une seule méthode d'instanciation par Factory)
* `Abstract Factory` : famille de produits liés fonctionnellement, plusieurs fabriques, plusieurs types d'objets retournés par chaque fabrique (plusieurs méthodes d'instanciation par Factory)
