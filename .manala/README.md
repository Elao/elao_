# Lazy - Symfony

* [Requirements](#requirements)

## Requirements

* Docker Desktop 2.2.0+

## Setup

Ok, let's init a `foo` symfony demo application:

```shell
manala init -i lazy.symfony foo
cd foo
```

Up the environment and shell into it into another terminal:

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
