# elao_

The Elao website.

## Prerequisite

Either:

- Docker
- or a local Node 16+, PHP 8.1+ & Symfony CLI install

## Setup

### Local install

For a local install, simply install the dependencies with:

```shell
make install
```

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

## Commands

When using Docker, use

```shell
make sh
```

to log into the container before running the next commands.

### Start the server

#### Local Install

If you use a local install & Symfony CLI, you can use:

```shell
make serve
```

The Symfony CLI exposes you the URL at which the site is available.

#### Using Docker

When using a Docker install, serve the application using:

```shell
make up
```

The site is now available at: http://localhost:8000, but you need to build or serve the assets (see following sections)

### Build assets

Build the assets once using:

```shell
make build.assets
```

For development purposes, **on a Docker install**, use a Webpack dev-server using:

```shell
make serve.assets
```

**When using a local install, `make serve` is enough to serve both PHP app and assets.**

## Contributing

### Writing an article

You can generate a new article using:

```shell
make article
```

Please, follow the [guidelines](https://elao.github.io/elao_/blog/styleguide/example/).

## Going further

- [Images & other assets](./res/docs/assets.md)
