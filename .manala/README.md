---
title: Lazy - Symfony
tableOfContent: 3
---

## Requirements

* [Manala CLI](https://manala.github.io/manala/installation/) to update the recipe
* Make
* Docker Desktop 2.2.0+ or Docker Engine + Docker Compose

## Setup

Ok, let's init a `foo` symfony demo application:

```shell
manala init -i lazy.symfony foo
cd foo
```

Up the system and open a shell into an another terminal:

```shell
make up
make sh
```

Create the `foo` symfony demo application into `/tmp` and move files into your project (_symfony can't create application in a non-empty dir_):

```shell
symfony new /tmp/foo --no-git --demo
rsync -a /tmp/foo/ ./
```

Browse http://localhost:8080 and enjoy :)
