.SILENT:
.PHONY: build

-include .manala/Makefile

## Install dependencies
install:
	composer install
	npm install

## Watch assets
watch:
	npm run watch

## Build assets
build:
	npm run build

## Build the site and serve the static version
serve-static:
	bin/console c:c --env=prod
	bin/console content:build --env=prod
	open http://localhost:8000
	php -S localhost:8000 -t build
