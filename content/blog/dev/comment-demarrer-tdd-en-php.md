---
type:               "post"
title:              "Comment démarrer en TDD en PHP ?"
date:               "2018-10-30"
publishdate:        "2018-10-30"
draft:              false

description:        "Test Driven Development c'est bien mais comment commencer à en faire ?"
tableOfContent:            true

thumbnail:          "images/posts/thumbnails/tdd.jpg"
header_img:         "images/posts/headers/tdd.jpg"
tags:               ["PHP", "Test", "TDD"]
categories:         ["Dev", "PHP"]

author:    "rhanna"
---

**Test Driven Development** ou le développement guidé par les tests, n'est clairement pas une méthode triviale.
N'ayez pas honte de ne pas savoir par où commencer.
Beaucoup de personnes parlent de « déclic » lorsqu'il s'agit d'appréhender le TDD.
L'ambition de cet article est d'essayer de créer ce déclic.

## Pourquoi faire du *Test Driven Development*

On trouve pléthore de littérature à ce sujet et voici selon nous les avantages à faire du TDD en vrac :

- tester les différents cas de figure,
- le plus souvent les tests s'appuient sur les spécifications et cela permet donc de conserver la documentation dans le code (*live documentation*),
- permet de mieux découper le code (notion de *clean code*),
- poser une première implémentation puis optimiser ou/et refactoriser et à chaque fois la valider grâce aux tests,
- gain de temps en validant l'implémentation via la console sans passer par les tests dans le navigateur.

<img src="https://media.giphy.com/media/iKbUlFbs77oI0/giphy.gif" />

## Outillage

Installons le framework de test PHPUnit :

    $ composer require --dev phpunit/phpunit ^7

En considérant que notre namespace est `MyCompany\App` on devrait avoir ceci dans notre composer.json :

```
{
  "autoload": {
    "psr-4": {
      "MyCompany\\App\\": "src"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "MyCompany\\App\\Tests\\": "tests"
    }
  },
  "require-dev": {
    "phpunit/phpunit": "^7.0"
  }
}
```

Vérifions que tout est en place en lançant :

    $ ./vendor/bin/phpunit

Nous devrions avoir quelque chose comme ça :

> No tests executed!

## Notre exemple

Imaginons que nous intervenons sur un blog.
Les utilisateurs de ce blog ont besoin de voir la liste des articles.
Pour chaque article, on voit un titre et le nombre de commentaires laissés.
Si un article a été publié il y a moins d'une semaine, le titre de l'article contient "Nouveau !" en préfixe.

### Hypothèses

Les articles sont stockés quelque part, en base de données, fichiers, peu importe...
Il me faut donc un service qui récupère les données puis les prépare pour être affichées.

On va considérer que :

- le ou les services pour récupérer les données existent déjà,
- l'affichage sera géré par autre chose, par le *controller* et le *templating* de notre application.

On va uniquement se focaliser pour cet exemple sur un service qui va préparer les données souhaitées.

Imaginer quelle est la plus petite entité logique à tester et faire cet effort de réflexion est finalement la
premère étape en TDD.

### Entité

Nous allons uniquement utiliser l'entité suivante `Post` représentant un article du blog :

```php
<?php

namespace MyCompany\App\Post;

class Post
{
    /** @var string */
    public $title;

    /** @var \DateTime */
    public $publishedAt;

    public function __construct(string $title, \DateTime $publishedAt)
    {
        $this->title = $title;
        $this->publishedAt = $publishedAt;
    }
}
```

### Dépendances

Considérons que nous avons les deux interfaces suivantes permettant d'accéder aux articles
et au nombre de commentaires par articles.

`PostRepository`:

```php
<?php

namespace MyCompany\App\Repository;

use MyCompany\App\Post\Post;

interface PostRepository
{
    /**
     * @return Post[]
     */
    public function getAll(): array;
}
```

`CommentRepository`:

```php
<?php

namespace MyCompany\App\Repository;

use MyCompany\App\Post\Post;

interface CommentRepository
{
    public function countByPost(Post $post): int;
}
```

### La plomberie

Créons une classe `PostView` qu'on appelle communément un DTO (data transfer object), un objet de transfert de données
qui ne contient aucun comportement, que des valeurs :

```php
<?php

namespace MyCompany\App\Post;

final class PostView
{
    /** @var string */
    public $title;

    /** @var int */
    public $commentsNumber;

    /** @var bool */
    public $isNew;

    public function __construct(string $title, int $commentsNumber, bool $isNew)
    {
        $this->title = $title;
        $this->commentsNumber = $commentsNumber;
        $this->isNew = $isNew;
    }
}
```

## Le service à tester

Créons notre service `GetPostsList` qui ne fait en réalité qu'une seule chose, une seule méthode publique, `_invoke` :

```php
<?php

namespace MyCompany\App\Post;

final class GetPostsList
{
    public function __invoke(): array
    {
        return [];
    }
}
```

### Les enfants et les tests d'abord !

Créons maintenant un test de `GetPostsList` qui est censé nous envoyer deux articles, l'un marqué nouveau, l'autre non.
Il est important de tester tous les cas. Cela tombe bien, notre besoin est simple, on a que deux cas.

Dans : `tests/Post/GetPostsListTest.php` :

```php
<?php

namespace MyCompany\App\Tests\Post;

use MyCompany\App\Post\GetPostsList;
use MyCompany\App\Post\PostView;
use PHPUnit\Framework\TestCase;

class GetPostsListTest extends TestCase
{
    public function test_get_new_and_old_posts()
    {
        $getPostsList = new GetPostsList();

        $this->assertEquals(
            [
                new PostView('TDD c\'est bien', 42, true), // is new
                new PostView('Tester ce n\'est pas douter', 1, false), // is not new
            ],
            $getPostsList()
        );
    }
}
```

Lançons les tests :

    $ ./vendor/bin/phpunit

Bien sûr, cela échoue :

    There was 1 failure:

    1) MyCompany\App\Tests\Post\GetPostsListTest::test_get_new_and_old_posts
    Failed asserting that two arrays are equal.
    --- Expected
    +++ Actual
    @@ @@
     Array (
    -    0 => MyCompany\App\Post\PostView Object (...)
    -    1 => MyCompany\App\Post\PostView Object (...)
     )

Mais pas de panique. Au contraire, faire échouer les tests est la 1ère étape en TDD !

Complétons le test en faisant un *mock* de chaque dépendance.
Le *mock* nous permet de s'affranchir de l'implémentation de la dépendance et de décider ce que celle-ci renvoie comme
données.

```php
<?php

namespace MyCompany\App\Tests\Post;

use MyCompany\App\Post\GetPostsList;
use MyCompany\App\Post\Post;
use MyCompany\App\Post\PostView;
use MyCompany\App\Repository\CommentRepository;
use MyCompany\App\Repository\PostRepository;
use PHPUnit\Framework\TestCase;

class GetPostsListTest extends TestCase
{
    public function test_get_new_and_old_posts()
    {
        $post1 = new Post('TDD c\'est bien', new \DateTime('2018-10-29'));
        $post2 = new Post('Tester ce n\'est pas douter', new \DateTime('2018-10-01'));

        // mock of PostRepository interface
        $postRepository = $this->prophesize(PostRepository::class);
        $postRepository->getAll()->shouldBeCalled()->willReturn([$post1, $post2]);

        // mock of CommentRepository interface
        $commentRepository = $this->prophesize(CommentRepository::class);
        $commentRepository->countByPost($post1)->shouldBeCalled()->willReturn(42);
        $commentRepository->countByPost($post2)->shouldBeCalled()->willReturn(1);

        $getPostsList = new GetPostsList($postRepository->reveal(), $commentRepository->reveal());

        $this->assertEquals(
            [
                new PostView('TDD c\'est bien', 42, true),
                new PostView('Tester ce n\'est pas douter', 1, false),
            ],
            $getPostsList()
        );
    }
}
```

### Enfin, l'implémentation

Nous avons tout ce qu'il faut pour implémenter notre code :

```php
<?php

namespace MyCompany\App\Post;

use MyCompany\App\Repository\CommentRepository;
use MyCompany\App\Repository\PostRepository;

final class GetPostsList
{
    /** @var PostRepository */
    private $postRepository;

    /** @var CommentRepository */
    private $commentRepository;

    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(): array
    {
        $postViews = [];

        foreach ($this->postRepository->getAll() as $post) {
            $isLessThanAWeekOld = (clone $post->publishedAt)->add(new \DateInterval('P1W')) > new \DateTime();

            $postViews[] = new PostView(
                $post->title,
                $this->commentRepository->countByPost($post),
                $isLessThanAWeekOld
            );
        }

        return $postViews;
    }
}
```

Enfin, lançons les tests :

    $ ./vendor/bin/phpunit

> OK (1 test, 4 assertions)

### Le temps n'attend pas

Attention, il y a un piège. Désolé mais ce test ne fonctionnera plus dans quelques jours.
Pourquoi ?
Nous avons une comparaison avec la date "système" dans l'implémentation (`new \DateTime`)
alors que les dates sont fixes dans les tests.

La date courante est une... dépendance. Modifions notre test pour fixer la date courante pour qu'elle ne bouge plus et
ne fasse plus échouer les tests dans quelques jours !

```php
<?php
// ...
class GetPostsListTest extends TestCase
{
    public function test_get_new_and_old_posts()
    {
        $now = new \DateTime('2018-10-30');
        // ...

        $getPostsList = new GetPostsList($postRepository->reveal(), $commentRepository->reveal(), $now);
```

Supprimons maintenant le `new \DateTime` dans la class GetPostsList et utilisons la date en dépendance :

```php
<?php
// ...
final class GetPostsList
{
    // ...

    /** @var \DateTime */
    private $now;

    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository, \DateTime $now)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->now = $now;
    }

    public function __invoke(): array
    {
        $postViews = [];

        foreach ($this->postRepository->getAll() as $post) {
            $isLessThanAWeekOld = (clone $post->publishedAt)->add(new \DateInterval('P1W')) > $this->now;
            // ...
```

Vérifions :

    $ ./vendor/bin/phpunit

> OK (1 test, 4 assertions)

Super !

Vous trouverez le code de cet exemple ici : https://github.com/supertanuki/TDD-en-PHP

## Conclusion

<img src="https://media.giphy.com/media/ds9RZ7gATF0wo/giphy.gif" />

J'espère que cet exemple vous aura convaincu des avantages du *Test Driven Development*.
Pour finir, un dernier conseil pour mettre le pied à l'étrier : sur votre projet en cours,
dès que vous devez faire un service qui fait quelque chose
d'assez simple avec une ou deux conditions, posez d'abord des tests vérifiant chacune des conditions avant d'implémenter
ledit service.

À vos marques, prêts, **testez** !

PS : si vous souhaitez vous éclater en faisant du TDD et du code de qualité,
<a href="/fr/elao/job-developpeur-backend-agence-paris-2018/">rejoignez-nous !</a>

