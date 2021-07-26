.SILENT:
.PHONY: build

-include .manala/Makefile

###########
# Install #
###########

## Install dependencies
install:
	symfony composer install
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
	npx encore dev-server --mode=development

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
	symfony console cache:clear
	symfony console stenope:build

## Build static site without resizing images, for moar speed
build-content-without-images: export APP_ENV = prod
build-content-without-images: export GLIDE_PRE_GENERATE_CACHE = 0
build-content-without-images:
	symfony console cache:clear
	symfony console stenope:build

## Build static site with assets
build-static: build build-content

## Serve the static version
serve-static:
	open http://localhost:8000
	symfony php -S localhost:8000 -t build

## Simulates GH Pages deploy into a subdir / with base url
build-subdir: export APP_ENV = prod
build-subdir: export WEBPACK_PUBLIC_PATH = /elao_/build
build-subdir: export ROUTER_DEFAULT_URI = http://localhost:8001/elao_
build-subdir: clear build
	rm -rf public/resized
	symfony console cache:clear
	symfony console stenope:build build/elao_

## Serve the static version of the site from a subdir / with base url
serve-static-subdir:
	open http://localhost:8001/elao_
	symfony php -S localhost:8001 -t build

########
# Lint #
########

lint: lint.php-cs-fixer lint.phpstan lint.twig lint.yaml lint.eslint lint.stylelint lint.container lint.composer

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
	npm run eslint

lint.stylelint:
	npm run stylelint

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

## Most basic test: check the build command, without images
test: build-content-without-images
test:
	$(call message_success, Most basic tests succeeded. You can ensure a \`make build-content\` is successful for more complete tests.)

############
# Utils    #
############

article:
	symfony console app:generate:article
