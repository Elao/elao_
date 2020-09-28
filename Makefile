.SILENT:
.PHONY: build

-include .manala/Makefile

###########
# Install #
###########

## Install dependencies
install:
	composer install
	npm install

###############
# Development #
###############

## Start dev server
start:
	symfony server:start

## Watch assets
watch:
	npm run watch

clear:
	rm -rf build public/build

#########
# Build #
#########

## Build static site with assets
build: build-assets build-content

## Build assets
build-assets:
	npm run build

## Build static site
build-content: export APP_ENV = prod
build-content:
	bin/console cache:clear
	bin/console content:build

## Build the site and serve the static version
serve-static: build-content
	open http://localhost:8000
	php -S localhost:8000 -t build

## Simulates GH Pages deploy into a subdir / with base url
build-subdir: export APP_ENV = prod
build-subdir: export WEBPACK_PUBLIC_PATH = /elao_/build
build-subdir: export ROUTER_DEFAULT_URI = http://localhost:8001/elao_
build-subdir: clear build-assets
	bin/console cache:clear
	bin/console content:build build/elao_

serve-static-subdir: build-subdir
	open http://localhost:8001/elao_
	php -S localhost:8001 -t build

########
# Lint #
########

lint: lint.php-cs-fixer lint.phpstan lint.twig@integration lint.yaml@integration lint.eslint

lint.php-cs-fixer:
	vendor/bin/php-cs-fixer fix

lint.php-cs-fixer@integration:
	vendor/bin/php-cs-fixer fix --dry-run --diff

lint.twig@integration:
	bin/console lint:twig templates --ansi --no-interaction

lint.yaml@integration:
	bin/console lint:yaml config translations --parse-tags --ansi --no-interaction

lint.phpstan: export APP_ENV = test
lint.phpstan:
	bin/console cache:clear --ansi
	bin/console cache:warmup --ansi
	vendor/bin/phpstan analyse

lint.phpstan@integration: export APP_ENV = test
lint.phpstan@integration:
	vendor/bin/phpstan --no-progress --no-interaction analyse

lint.eslint:
	npm run fix

lint.eslint@integration:
	npm run lint

############
# Security #
############

security.symfony@integration:
	symfony check:security

security.npm@integration:
	npm audit ; RC=$${?} ; [ $${RC} -gt 2 ] && exit $${RC} || exit 0

############
# Test #
############

test:
	 ./bin/phpunit
