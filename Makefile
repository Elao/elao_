.SILENT:
.PHONY: build

PORT_PREFIX = 350
PROJECT_DOMAIN = www.ela.ooo

define manala_project_host
$(PROJECT_DOMAIN)$(if $(1),:$(PORT_PREFIX)$(shell printf "%02d" $(1)))
endef

-include .manala/Makefile

## System - Setup the project for the first time with Docker
setup:
	WAIT=1 make up
	$(manala_docker_command) make install build.assets
	$(call manala_message, Will be available at http://$(call manala_project_host, 80))

## System - Stop local system
halt: stop

###########
# Install #
###########

## Install - Install dependencies
install: install.composer install.npm

install.composer:
	symfony composer install

install.npm:
	npm install

## Update - Update dependencies
update: update.composer update.npm update.browserslist

update.composer:
	symfony composer update

update.npm:
	npm update

update.browserslist:
	npx update-browserslist-db@latest

###############
# Development #
###############

ifndef MANALA_DOCKER
## Dev - Start the whole application for development purposes (local only)
serve:
	# https://www.npmjs.com/package/concurrently
	npx concurrently "make serve.php" "make serve.assets" --names="Symfony,Webpack" --prefix=name --kill-others --kill-others-on-fail
else
## Dev - Start the application dependant processes for development purposes (docker only)
serve:
	$(call manala_message, Will be available at http://$(call manala_project_host, 80))
	make serve.assets
endif

ifndef MANALA_DOCKER
## Dev - Start Symfony server
serve.php: export SYMFONY_PORT ?= $(PORT_PREFIX)80
serve.php:
	$(call manala_message, Will be available at http://$(call manala_project_host):$(SYMFONY_PORT) )
	-open http://www.ela.ooo:$(SYMFONY_PORT)
	symfony server:start --no-tls --port=$(SYMFONY_PORT)
endif

## Dev - Start webpack dev server with HMR (Hot reload)
serve.assets: export WEBPACK_PORT ?= $(PORT_PREFIX)81
serve.assets:
	npx encore dev-server \
		--mode=development \
		--port=$(WEBPACK_PORT) \
		--host=$(PROJECT_DOMAIN)

## Dev - Watch assets
watch.assets:
	npm run watch

## Clear - Clear the build dir and assets
clear.build:
	rm -rf build public/build

## Clear - Clear resized images cache
clear.images:
	rm -rf public/resized

#########
# Build #
#########

build: build.assets

## Build - Build assets
build.assets:
	npm run build

## Build - Build static site
build.content: export APP_ENV = prod
build.content:
	rm -rf public/resized
	symfony console cache:clear
	ulimit -S -n 2048 && symfony console stenope:build

## Build - Build static site without resizing images, for moar speed
build.content.without-images: export APP_ENV = prod
build.content.without-images: export GLIDE_PRE_GENERATE_CACHE = 0
build.content.without-images:
	symfony console cache:clear
	ulimit -S -n 2048 && symfony console stenope:build

## Build - Build static site with assets
build.static: build.assets build.content

## Serve - Serve the static version
serve.static:
	open http://localhost:8000
	symfony php -S localhost:8000 -t build

## Build - Simulates GH Pages deploy into a subdir / with base url
build.static.subdir: export APP_ENV = prod
build.static.subdir: export WEBPACK_PUBLIC_PATH = /elao_/build
build.static.subdir: export ROUTER_DEFAULT_URI = http://localhost:8001/elao_
build.static.subdir: clear.build build.assets
	rm -rf public/resized
	symfony console cache:clear
	ulimit -S -n 2048 && symfony console stenope:build build/elao_

## Serve - Serve the static version of the site from a subdir / with base url
serve.static.subdir:
	open http://localhost:8001/elao_
	symfony php -S localhost:8001 -t build

########
# Lint #
########

## Lint - Lint
lint: lint.php-cs-fixer lint.phpstan lint.twig lint.yaml lint.eslint lint.container lint.composer

lint.composer:
	symfony composer validate --no-check-publish

lint.container:
	symfony console lint:container

lint.php-cs-fixer:
	symfony php vendor/bin/php-cs-fixer fix

lint.twig:
	symfony console lint:twig templates --show-deprecations

lint.yaml:
	symfony console lint:yaml config translations --parse-tags

lint.phpstan: export APP_ENV = test
lint.phpstan:
	symfony console cache:clear --ansi
	symfony console cache:warmup --ansi
	symfony php vendor/bin/phpstan analyse --memory-limit=-1

lint.eslint:
	npm run fix

############
# Security #
############

security.symfony:
	symfony check:security

security.npm:
	npm audit ; RC=$${?} ; [ $${RC} -gt 2 ] && exit $${RC} || exit 0

#######
# SSH #
#######

ssh@production:
	ssh app@website-1.elao.prod.elao.run

########
# Test #
########

## Test - Most basic test: check the build command, without images
test: build.content.without-images
test:
	$(call manala_message_success, Most basic tests succeeded. You can ensure a \`make build.content\` is successful for more complete tests.)

############
# Utils    #
############

## Content - Generate a new article
article:
	symfony console app:generate:article
