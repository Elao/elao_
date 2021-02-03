# elao_

The Elao website.

## Prerequisite

Either:

- Docker
- or a local Node 12+, PHP 7.4+ & Symfony CLI install

## Setup

If you want to use the Docker stack, setup the project using:

    make up

Then, log into the container using

    make sh

And install the dependencies with

    make install

(for a local installation, simply run `make install`).

## Contributing

When Docker, use
    
    make sh

to log into the container before running the next commands.

## Start the server

    make up

The site is now available at: http://localhost:8080

Alternatively, if you use a local install & Symfony CLI, you can use:

    make start

The Symfony CLI exposes you the URL at which the site is available.

## Build assets

    make build-assets

### Start asset watcher

    make watch
