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

## Start the whole application for development purposes (local only)
start:
	# https://www.npmjs.com/package/concurrently
	npx concurrently "make serve" "make dev" --names="Symfony,Webpack" --prefix=name --kill-others --kill-others-on-fail

## Start Symfony server
serve:
	symfony server:start --no-tls

## Watch assets
watch:
	npm run watch

## Start webpack dev server with HMR (Hot reload)
dev:
	npx encore dev-server --mode=development --port=8085

## Clear the build and assets
clear:
	rm -rf build public/build

#########
# Build #
#########

## Build assets
build:
	npm run build

## Build static site
build-content: export APP_ENV = prod
build-content:
	rm -rf public/resized
	bin/console cache:clear
	bin/console stenope:build

## Build static site without resizing images, for moar speed
build-content-without-images: export APP_ENV = prod
build-content-without-images: export GLIDE_PRE_GENERATE_CACHE = 0
build-content-without-images:
	bin/console cache:clear
	bin/console stenope:build

## Build static site with assets
build-static: build build-content

## Serve the static version
serve-static:
	open http://localhost:8000
	php -S localhost:8000 -t build

## Simulates GH Pages deploy into a subdir / with base url
build-subdir: export APP_ENV = prod
build-subdir: export WEBPACK_PUBLIC_PATH = /elao_/build
build-subdir: export ROUTER_DEFAULT_URI = http://localhost:8001/elao_
build-subdir: clear build
	rm -rf public/resized
	bin/console cache:clear
	bin/console stenope:build build/elao_

## Serve the static version of the site from a subdir / with base url
serve-static-subdir:
	open http://localhost:8001/elao_
	php -S localhost:8001 -t build

########
# Lint #
########

lint: lint.php-cs-fixer lint.phpstan lint.twig@integration lint.yaml@integration lint.eslint lint.container lint.composer

lint.composer:
	composer validate --no-check-publish

lint.composer@integration:
	composer validate --no-check-publish --ansi --no-interaction

lint.container:
	bin/console lint:container

lint.container@integration:
	bin/console lint:container --ansi --no-interaction

lint.php-cs-fixer:
	vendor/bin/php-cs-fixer fix

lint.php-cs-fixer@integration:
	vendor/bin/php-cs-fixer fix --dry-run --diff

lint.twig@integration:
	bin/console lint:twig templates --show-deprecations --ansi --no-interaction

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
# SSH #
############

ssh@production:
	ssh app@website-1.elao.prod.elao.run
