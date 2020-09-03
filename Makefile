.SILENT:
.PHONY: build

-include .manala/Makefile

## Install dependencies
install:
	composer install
	npm install

## Start dev server
start:
	symfony server:start

## Watch assets
watch:
	npm run watch

## Build static site with assets
build: build-assets build-content

## Build assets
build-assets:
	npm run build

## Build static site
build-content:
	bin/console content:build --env=prod

## Build the site and serve the static version
serve-static:
	bin/console c:c --env=prod
	bin/console content:build --env=prod
	open http://localhost:8000
	php -S localhost:8000 -t build
