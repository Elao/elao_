---
type:           "post"
title:          "Améliorez la pertinence de vos résultats ElasticSearch grâce au score"
date:           "2017-04-24"
publishdate:    "2017-04-27"
draft:          false

description:    "Améliorez la pertinence de vos résultats ElasticSearch grâce au score."

thumbnail:      "/images/posts/thumbnails/elasticsearch.png"
header_img:     "/images/posts/headers/elasticsearch.jpg"
tags:           ["moteur de recherche", "recherche", "elasticsearch", "pertinence", "score", "ES", "elastica"]
categories:     ["dev"]

author: "mcolin"

---

## ElasticSearch

[ElasticSearch](https://www.elastic.co/fr/products/elasticsearch) est un moteur de recherche très puissant mais relativement simple à mettre en place et à intégrer grâce à son API RESTful. Des bibliothèques telles que le client PHP [Elastica](http://elastica.io/) et le bundle Symfony [FOSElasticaBundle](https://github.com/FriendsOfSymfony/FOSElasticaBundle) facilitent encore plus son intégration. Néanmoins la configuration fine du moteur de recherche reste assez complexe et peut faire peur au premier abord.

Je ne vais pas parler de la configuration serveur et infrastructure d'ElasticSearch qui touche plus aux performances et à la sécurité de l'outil mais plutôt m'attarder sur la configuration du moteur de recherche en lui-même, de ce qui impactera la pertinence de vos résultats.

Deux choses vont impacter les résultats de vos recherches : l'**indexation** de vos données et vos **requêtes** de recherche. Ce sont donc ces deux points qui vont être abordés dans cet article.

## Une histoire de score

Lors d'une recherche ElasticSearch, un score est calculé pour chaque document du résultat. Ce score est censé représenter la pertinence du document afin de pouvoir ordonner les résultats. Néanmoins il ne représente que la pertinence des résultats face aux paramètres de la recherche et d'indexation.

Pour calculer ce score, ElasticSearch va s'appuyer sur trois critères :

* La **fréquence du terme** recherché dans le document. Plus le terme est fréquent, plus son poids sera élevé.
* La **fréquence inverse du terme** à travers tous les documents. Plus le terme est fréquent, moins il aura de poids.
* La **longueur du champ**. Plus le champ est grand, plus le poids sera faible ; inversement, plus le champ est petit, plus le poids sera élevé.

Par defaut, ElasticSearch combine ces 3 règles pour obtenir un score, mais certaines peuvent être désactivées si elles ne vous semblent pas correspondre à vos données. Pour plus d'informations sur le calcul du score, lisez la [théorie du score](https://www.elastic.co/guide/en/elasticsearch/guide/current/scoring-theory.html) sur le site d'ElasticSearch.

Ces règles permettent déjà d'avoir une bonne notion de pertinence mais restent assez simples et ne prennent pas en compte le métier de vos données. Pour ajouter plus de logique dans les scores, vous devrez introduire vos propres règles qui influenceront voire remplaceront le score.

## Indexation

L'indexation est la première étape lorsque qu'il s'agit d'optimiser la pertinence de son moteur de recherche. Car c'est grâce aux données indéxées qu'ElasticSearch va calculer les scores.

### Typage

ElasticSearch propose un [large choix de types](https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping-types.html) pour vos données. Il a de nombreux types spéciaux qui n'existent pas dans les languages de programmation tels que `geo_point` ou `ip`. Il est important de typer correctement ses données car ElasticSearch dispose de traitements optimisés pour chaque type.

### Analyser

L'`analyser` est chargé d'examiner les données a indéxer afin de les stocker de la façon la plus optimale pour les recherches. Cette partie est très importante car des données mal indéxées ne permettront pas une recherche pertinente. Il faut donc choisir avec soin l'`analyser` pour chaque type de donnée que vous souhaitez indéxer. Ce choix est d'autant plus important pour les données complexes telles qu'un texte.

ElasticSearch propose [plusieurs `analysers`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-analyzers.html) configurables. Chaque `analyzer` est une combinaison d'un [`tokeniser`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-tokenizers.html) chargé de découper votre donnée en tokens, de `char filters` chargés de filtrer les caractères et de `token filters` chargés de filtrer les tokens.

Vous pouvez également [créer votre propre `analyzer`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-custom-analyzer.html) en combinant vous-même `tokeniser`, `char filters` et `token filters`. Pour configurer un moteur de recherche efficace, il est donc recommandé de choisir ou créer un `analyser` adapté à chacune des données indéxées.

Par exemple, pour obtenir une indexation efficace d'un texte, il existe quelques filtres très importants à mettre en place :

* [`stemmer`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stemmer-tokenfilter.html#analysis-stemmer-tokenfilter) : Permet une analyse linguistique de votre texte basée sur les racines des mots dans une langue donnée (une recherche sur le mot "collection" trouvera ainsi les mots "collectionner" ou "collectionneur" par exemple).
* [`stop`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-stop-tokenfilter.html) : Permet de filtrer les *stop words*, c'est-à-dire les mots de liaison qui ne sont pas porteurs de sens et qui ne feraient que polluer l'index (en français par exemple : "de", "en", "à", "le", "la", ...).
* [`keyword_marker`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-keyword-marker-tokenfilter.html) : Permet d'indiquer des mots clés à considérer comme un seul token et non comme plusieurs mots (par exemple "service worker" ou "sous domaine" sont des mots clés).
* [`lowercase`](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lowercase-tokenfilter.html) : Permet de tout indexer en *lowercase* afin de ne pas être sensible à la casse.

Il existe bien évidemment [des `analysers` par langue déjà prêts à l'emploi](https://www.elastic.co/guide/en/elasticsearch/reference/current/analysis-lang-analyzer.html#french-analyzer), mais l'idée est de vous montrer qu'il est important de bien indiquer à **ElasticSearch** comment analyser vos données.

### Boost

Vous pouvez ajouter dans votre mapping des [boost](https://www.elastic.co/guide/en/elasticsearch/reference/current/mapping-boost.html) sur certaines propriétés afin de privilégier automatiquement ces propriétés lors du calcul de pertinence.

<div class="tabs">
<div class="nav">
<a href="#mapping-boost-json" class="active">json</a>
<a href="#mapping-boost-yaml">yaml</a>
</div>
<div class="tab active" id="mapping-boost-json">
<code highlight="json">
{
  "mappings": {
    "article": {
      "properties": {
        "title": {
          "type": "text",
          "boost": 3
        },
        "content": {
          "type": "text"
        }
      }
    }
  }
}
</code>
</div>
<div class="tab" id="mapping-boost-yaml">
<code highlight="yaml">
fos_elastica:
  indexes:
    app:
      types:
        article:
          mappings:
            title:   { analyzer: my_analyzer, boost: 3 }
            content: { analyzer: my_analyzer }
</code>
</div>
</div>

Dans cet exemple, le titre aura 3 fois plus de poids que le contenu lors du calcul de pertinence.

<div style="border-left: 5px solid #ffa600;padding: 20px;margin: 20px 0;">
    Attention, les `boosts` indiqués au `mapping` ne fonctionneront que sur les requêtes de type `term`. Pour les requêtes de type `range` ou `match` par exemple, il faudra préciser les `boosts` dans la requête comme expliqué dans la suite de l'article.
</div>

## Requêter

### Analyzer

Pour utiliser votre `analyser` lors de la recherche, vous devez le préciser dans votre requête. Vous pouvez compléter votre requête avec les options `fuzziness` et `minimum_should_match`.

[`minimum_should_match`](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-minimum-should-match.html) permet d'indiquer le pourcentage minimum de votre recherche qui doit être trouvé dans vos documents.

[`fuzziness`](https://www.elastic.co/guide/en/elasticsearch/reference/current/common-options.html#fuzziness) permet de rechercher des termes malgré des fautes de frappe (inversion de lettre, lettre manquante, ...) en utilisant la [Distance de Levenshtein](https://fr.wikipedia.org/wiki/Distance_de_Levenshtein).

Les scores des résultats seront bien évidemment impactés par ces options.

<div class="tabs">
<div class="nav">
<a href="#query-analyzer-json" class="active">json</a>
<a href="#query-analyzer-php">php</a>
</div>
<div class="tab active" id="query-analyzer-json">
<code highlight="json">
{
  "query": {
    "bool": {
      "must": [
        { "match": {
          "title": {
            "query": "Foobar",
            "analyser": "my_analyser",
            "fuzziness": "AUTO",
            "minimum_should_match": "70%"
          }
        }}
      ]
    }
  }
}
</code>
</div>
<div class="tab" id="query-analyzer-php">
<code highlight="php">
<?php
use Elastica\Query;

$query = (new Query\Match())
    ->setFieldQuery('title', $search)
    ->setFieldAnalyzer('title', 'my_analyzer')
    ->setFieldFuzziness('title', 'AUTO')
    ->setFieldMinimumShouldMatch('title', '70%')
);

</code>
</div>
</div>

### Boost

Les [`boost`](https://www.elastic.co/guide/en/elasticsearch/guide/current/_boosting_query_clauses.html) permettent également d'augmenter le poids d'une clause de votre rêquete. Plus le boost est élevé, plus votre clause pèsera sur le score.

Dans l'exemple suivant, nous faisons une recherche de la chaine `Foobar` sur un document ayant un titre et un contenu. Grâce aux `boost` nous pouvons donner plus d'importance aux titres qu'aux contenus.

<div class="tabs">
<div class="nav">
<a href="#boost-json" class="active">json</a>
<a href="#boost-php">php</a>
</div>
<div class="tab active" id="boost-json">
<code highlight="json">
{
  "query": {
    "bool": {
      "should": [
        { "match": {
          "title": { "query": "Foobar", "boost": 5 }
        }},
        { "match": {
          "content": { "query": "Foobar", "boost": 2 }
        }}
      ]
    }
  }
}
</code>
</div>
<div class="tab" id="boost-php">
<code highlight="php">
<?php
use Elastica\Query;

$query = new Query\Bool();

$query->addShould((new Query\Match())
    ->setFieldQuery('title', $search)
    ->setFieldBoost('title', 5)
);

$query->addShould((new Query\Match())
    ->setFieldQuery('content', $search)
    ->setFieldBoost('content', 2)
);
</code>
</div>
</div>

Vous pouvez également utiliser plusieurs `boost` sur la même propriété mais avec plusieurs valeurs afin d'augmenter le score par palier.

<div class="tabs">
<div class="nav">
<a href="#boost-step-json" class="active">json</a>
<a href="#boost-step-php">php</a>
</div>
<div class="tab active" id="boost-step-json">
<code highlight="json">
{
  "query" : {
    "bool" : {
      "should" : [
        { "range" : {
          "publishedAt" : { "boost" : 5, "gte" : "<1 month ago>" }
        }},
        { "range" : {
          "publishedAt" : { "boost" : 4, "gte" : "<2 months ago>" }
        }},
        { "range" : {
          "publishedAt" : { "boost" : 3, "gte" : "<3 months ago>" }
        }}
      ]
    }
  }
}
</code>
</div>
<div class="tab" id="boost-step-php">
<code highlight="php">
<?php
use Elastica\Query;

$bool = new Query\Bool();

$query->addShould((new Query\Range('publishedAt', [
    'boost' => 5,
    'gte'   => (new \DateTime('-1 month'))->format('c'),
])));

$query->addShould((new Query\Range('publishedAt', [
    'boost' => 4,
    'gte'   => (new \DateTime('-2 months'))->format('c'),
])));

$query->addShould((new Query\Range('publishedAt', [
    'boost' => 3,
    'gte'   => (new \DateTime('-3 months'))->format('c'),
])));
</code>
</div>
</div>

### Les fonctions de score

Les fonctions de score permettent de modifier le score de vos résultats.

Il existe plusieurs types de fonctions de score :

* `script_score`
* `weight`
* `random_score`
* `field_value_factor`
* `decay functions`

Je vais surtout détailler les fonctions `script` et `decay` car ce sont celles qui permettent le plus d'implémenter une logique de pertinence. Pour les autres vous pouvez lire [la documentation sur les fonctions de score](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#score-functions).

#### Les scripts de score

Les scripts de score ([`script_score`](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-script-score)) vous permettent de modifier le score de vos résultats à partir d'un script ou d'une formule de votre choix. Vous avez accès au document dont vous modifiez le score et pouvez donc utiliser l'une de ses propriétés dans le calcul. `_score` est une variable qui contient le score original.

<div class="tabs">
<div class="nav">
<a href="#script-functions-json" class="active">json</a>
<a href="#script-functions-php">php</a>
</div>
<div class="tab active" id="script-functions-json">
<code highlight="json">
{
    "script_score" : {
        "script" : {
          "lang": "painless",
          "inline": "_score * doc['my_numeric_field'].value"
        }
    }
}
</code>
</div>
<div class="tab" id="script-functions-php">
<code highlight="php">
<?php
use Elastica\Query;

$bool = new Query\Bool();

$score = new Query\FunctionScore();
$score->addScriptScoreFunction(
    new \Elastica\Script("_score * doc['my_numeric_field'].value")
);

$score->setQuery($bool);

$query = new Query($score);
</code>
</div>
</div>

Vous pouvez ainsi utiliser une valeur ou une formule métier pour calculer la pertinence de vos résultats.

#### Facteur

Cette fonction de score ([`field_value_factor`](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-field-value-factor)) vous permet d'appliquer un facteur de multiplication (`factor`), une valeur par defaut (`missing`) ainsi qu'une fonction mathématique (`modifier`) à une propriété de votre document. Plusieurs fonctions mathématiques sont disponibles (`log`, `sqrt`, `ln`, ...).

<div class="tabs">
<div class="nav">
<a href="#factor-functions-json" class="active">json</a>
<a href="#factor-functions-php">php</a>
</div>
<div class="tab active" id="factor-functions-json">
<code highlight="json">
{
    "field_value_factor": {
        "field": "rate",
        "factor": 1.1,
        "modifier": "sqrt",
        "missing": 1
    }
}
</code>
</div>
<div class="tab" id="factor-functions-php">
<code highlight="php">
<?php
use Elastica\Query;

$bool = new Query\Bool();

$score = new Query\FunctionScore();
$score->addFieldValueFactorFunction(
    'rate',
    1.1,
    Query\FunctionScore::FIELD_VALUE_FACTOR_MODIFIER_SQRT,
    1
);

$score->setQuery($bool);

$query = new Query($score);
</code>
</div>
</div>

Dans cet exemple, la pertinence d'un résultat repose sur la note du document via la formule suivante : `sqrt(1.1 * doc.rate)`.

#### Les fonctions de décroissance

Les fonctions de décroissance ([`decay function`](https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-function-score-query.html#function-decay)) sont une autre méthode pour modifier le score de vos résultats. Elles se basent sur des fonctions mathématiques pour réduire le score de vos résultats.

<div class="tabs">
<div class="nav">
<a href="#decay-functions-json" class="active">json</a>
<a href="#decay-functions-php">php</a>
</div>
<div class="tab active" id="decay-functions-json">
<code highlight="json">
{
    "DECAY_FUNCTION": {
        "FIELD_NAME": {
              "origin": "2017-04-24",
              "offset": "1d",
              "scale": "5d",
              "decay": 0.5
        }
    }
}
</code>
</div>
<div class="tab" id="decay-functions-php">
<code highlight="php">
<?php
use Elastica\Query;

$bool = new Query\Bool();

$score = new Query\FunctionScore();
$score->addDecayFunction(
    Query\FunctionScore::DECAY_LINEAR,
    'publishedAt',
    '2017-04-24',
    '5d',
    '1d',
    0.90
);

$score->setQuery($bool);

$query = new Query($score);
</code>
</div>
</div>

Chaque fonction de décroissance est caratérisée par les propriétés `origin`, `offset`, `scale` et `decay`.

* `origin` est la valeur centrale à partir de laquelle sera calculée la distance de vos résultats. D'une manière générale, plus vos résultats s'éloigneront de cette valeur centrale, plus le score sera réduit.
* `offset` est la distance à partir de laquelle s'appliquera votre fonction de décroissance. Avant cette distance le score ne sera pas modifié.
* `scale` est la valeur à laquelle votre fonction de décroissance appliquera la réduction souhaitée.
* `decay` est la valeur de réduction de score souhaitée (pourcentage de 0 à 1).

Dans l'exemple ci-dessus, la valeur centrale est le 24 avril 2017 et on souhaite qu'à 6 jours (1 jour d'offset + 5 jours de scale) de cette date, soit le 18 et le 30 avril, le score soit réduit de moitié. La réduction du score des autres résultats sera calculée par la fonction de décroissance choisie.

Il existe 3 fonctions de décroissances, [linéaire](https://fr.wikipedia.org/wiki/Fonction_lin%C3%A9aire), [exponentielle](https://fr.wikipedia.org/wiki/Fonction_exponentielle) et [gaussienne](https://fr.wikipedia.org/wiki/Fonction_gaussienne).

La fonction linéaire est une droite, la décroissance est proportionelle à la distance. Avec la fonction exponentielle, la décroissance est très forte au début et diminue rapidement avec la distance jusqu'à tendre vers zero. Avec la fonction gaussienne, la décroissance est également très forte au début mais diminue moins rapidement.

![Decay functions](/images/posts/2017/es-decay-graph.png)

Les fonctions de décroissance peuvent être appliquées sur des valeurs numériques, des dates (`offset` et `scale` sont alors exprimés en durée : 5h ou 1d par exemple) ou des géopoints (`offset` et `scale` sont alors exprimés en distance : 100m ou 5km par exemple).

## Conclusion

Avec toutes ces fonctionnalités, vous dévriez être capables de gérer la pertinence de votre moteur de recherche assez finement. Attention néanmoins, cet article n'est pas exhaustif, **ElasticSearch** propose bien d'autres possibilités.

L'important est de ne pas se limiter à la configuration de base et d'adapter l'algorithme de score à vos données et vos besoins.

<style type="text/css">
    .container .tabs .nav { background: #ccc; }
    .container .tabs .nav:before, .tabs .nav:after { content: ""; display: table; clear: both; }
    .container .tabs .nav a { float: left; display: block; padding: 5px 10px; margin: 0; text-decoration: none; }
    .container .tabs .nav a.active { background-color: #444; color: #fff; }
    .container .tabs .tab { display: none; clear: both; }
    .container .tabs .tab.active { display: block; }
</style>

<script type="text/javascript">
    function Tabs (element)
    {
        this.element = element;
        this.links   = element.querySelectorAll('.nav a');
        this.tabs    = element.querySelectorAll('.tab');

        [].forEach.call(this.links, function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                this.open(link);
            }.bind(this));
        }.bind(this));
    }

    Tabs.prototype.open = function (link)
    {
        [].forEach.call(this.links, function (link) { link.classList.remove('active'); });
        [].forEach.call(this.tabs, function (tab) { tab.classList.remove('active'); });

        link.classList.add('active');
        this.element.querySelector(link.getAttribute('href')).classList.add('active');
    };

    [].forEach.call(document.querySelectorAll('.tabs'), function (element) { new Tabs(element); });
</script>
