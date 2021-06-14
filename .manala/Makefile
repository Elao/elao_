# Relative root dir ("."|".."|"../.."|…)
_DIR := $(patsubst ./%,%,$(patsubst %/.manala/Makefile,%,./$(filter %.manala/Makefile,$(MAKEFILE_LIST))))
# Is current dir root ? (""|"1")
_ROOT = $(if $(filter .,$(_DIR)),1)
# Relative current dir ("."|"foo"|"foo/bar"|…)
_CURRENT_DIR = $(patsubst ./%,%,.$(patsubst $(realpath $(CURDIR)/$(_DIR))%,%,$(CURDIR)))
# Relative cache dir ("./.manala/.cache"|"../.manala/.cache"|"../../.manala/.cache"|…)
_CACHE_DIR = $(shell dir=$(_DIR)/.manala/.cache ; mkdir -p $${dir} ; echo $${dir})

include $(_DIR)/.manala/make/text.mk
include $(_DIR)/.manala/make/help.mk
include $(_DIR)/.manala/make/os.mk
include $(_DIR)/.manala/make/math.mk
include $(_DIR)/.manala/docker/make.mk

##########
# System #
##########

ifneq ($(container),docker)

_DOCKER_COMPOSE_PROJECT_NAME = elao
_DOCKER_COMPOSE_FILE = $(_DIR)/.manala/docker-compose.yaml $(_DIR)/.manala/docker-compose.$(OS).yaml
_DOCKER_COMPOSE_SERVICE = system
_DOCKER_COMPOSE_USER = lazy
_DOCKER_COMPOSE_ENV = \
	DIR=$(realpath $(_DIR)) \
	CACHE_DIR=$(realpath $(_CACHE_DIR)) \
	USER_ID=$(shell id -u) \
	GROUP_ID=$(shell id -g) \

HELP += $(call help_section, System)

HELP += $(call help,up,   Create and start local system)
up:
	@$(call log, Builds$(,) creates and starts docker containers…)
	$(_docker_compose) up
.PHONY: up

HELP += $(call help,sh,   Open a local system shell)
sh:
	$(call docker_exec, exec bash --login)
.PHONY: sh

HELP += $(call help,clean,Clean local system)
clean:
	@$(call confirm, Confirm system cleaning)
	@$(call log, Stops and removes docker containers$(,) networks$(,) volumes$(,) images…)
	$(_docker_compose) down \
		--rmi all \
		--volumes \
		--remove-orphans
	@$(call log, Removes old docker images…)
	$(_docker) images \
		--filter reference='elao:*' \
		--format "{{.ID}}" \
			| xargs $(_docker) rmi --force
	@$(call log, Delete cache dir…)
	rm -Rf $(_CACHE_DIR)
.PHONY: clean

endif
