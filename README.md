# elao_

The Elao website.

## Prerequisite

Either:

- Docker
- or a local install, with Node 16+, PHP 8.1+ & Symfony CLI

## Setup

### Local Install

For a local install, simply install the dependencies with:

```shell
make install
```

> **Note**
> You're done! **Next:** see how to [serve the app](#dev).

### Docker

If you want to use the Docker stack, setup the project using:

```shell
make up
```

Then, log into the container using

```shell
make sh
```

And install the dependencies with

```shell
make install
```

## Dev

### Local Install

If you use a **local install**, use

```shell
make serve
```

The Symfony CLI exposes you the URL at which the site is available.

> **Note**
> When using a local install, `make serve` is enough to serve both PHP app and assets.  
> You're ready to dev!

### Using Docker

When using a Docker install, serve the PHP application using:

```shell
make up
```

The site is now available at: http://localhost:8000, but you need to build or serve the assets:

For development purposes, start a Webpack dev-server using:

```shell
make serve.assets
```

## Commands

> **Warning**
> When **using Docker** (_not the local install_), you must use
>
> ```shell
> make sh
> ```
> to log into the container before running any command.

### Build assets

Build the assets once using:

```shell
make build.assets
```

## Contributing

### Writing an article

You can generate a new article using:

```shell
make article
```

Please, follow the [guidelines](https://elao.github.io/elao_/blog/styleguide/example/).

## Going further

- [Images & other assets](./res/docs/assets.md)
