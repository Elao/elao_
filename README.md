# elao_

The Elao website.

## Prerequisite

Either:

- a local install, with:
  - Node 16+, 
  - PHP 8.1+
  - [Symfony CLI](https://symfony.com/download)
- or Docker, thanks to the [`lazy.symfony` Manala recipe](https://manala.github.io/manala-recipes/recipes/lazy.symfony/)

## Setup

### Local Install

Install the dependencies using

```shell
make install
```

> [!Note]
> You're done! **Next:** see how to [serve the app](#dev).

<details>
<summary>
<h3>Using Docker</h3>
</summary>

If you want to use the Docker stack, setup the project using:

```shell
make up
```

Then, log into the container using

```shell
make sh
```

> [!Warning]
> When **using Docker**, you must use `make sh`
> to log into the container before running any command.

And install the dependencies with

```shell
make install
```
</details>

## Dev

### Local Install

Start a server using

```shell
make serve
```

The Symfony CLI exposes you the URL at which the site is available.

> [!Note]
> When using a local install, `make serve` is enough to serve both PHP app and assets.  
> You're ready to dev!

<details>
<summary>
<h3>Using Docker</h3>
</summary>

When using a Docker install, serve the PHP application using:

```shell
make up
```

> [!Warning]
> The site is now available at http://www.ela.ooo:35080, but you need to build or serve the assets.

For development purposes, start a Webpack dev-server using:

```shell
make serve.assets
```
</details>

## Commands

### Build assets

Build the assets once using:

```shell
make build.assets
```

### Writing an article

Generate a new article using:

```shell
make article
```

Please, follow the [guidelines on how to write an article](https://elao.github.io/elao_/blog/styleguide/example/).

## Going further

- [Images & other assets](./res/docs/assets.md)
