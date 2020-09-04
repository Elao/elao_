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
