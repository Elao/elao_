# Relative root dir ("."|".."|"../.."|…)
_ROOT_DIR := $(patsubst ./%,%,$(patsubst %/.manala/Makefile,%,./$(filter %.manala/Makefile,$(MAKEFILE_LIST))))
# Is current dir root ? (""|"1")
_ROOT := $(if $(filter .,$(_ROOT_DIR)),1)
# Relative current dir ("."|"foo"|"foo/bar"|…)
_DIR := $(patsubst ./%,%,.$(patsubst $(realpath $(CURDIR)/$(_ROOT_DIR))%,%,$(CURDIR)))

include $(_ROOT_DIR)/.manala/make/text.mk
include $(_ROOT_DIR)/.manala/make/help.mk
include $(_ROOT_DIR)/.manala/make/os.mk

###############
# Environment #
###############

ifneq ($(container),docker)

export COMPOSE_FILE         = $(_ROOT_DIR)/.manala/docker-compose.yaml
export COMPOSE_PROJECT_NAME = elao

HELP += $(call help_section, Environment)

HELP += $(call help,up,        Create and start environment (BUILD))
up:
	$(call message,Create and start environment...)
	mkdir -p $(_ROOT_DIR)/.manala/.cache
	OS=$(OS) docker-compose up \
		$(if $(BUILD),--build --remove-orphans)

HELP += $(call help,clean,     Clean environment)
clean:
	$(call confirm,Please$(,) confirm environment cleaning)
	OS=$(OS) docker-compose down \
		--rmi all \
		--volumes \
		--remove-orphans

HELP += $(call help,sh,        Shell environment)
sh:
	docker-compose exec \
		--user app \
		app \
		bash -l

HELP += $(call help,sh.nginx,  Shell environment - Nginx)
sh.nginx:
	docker-compose exec \
		nginx \
		bash

HELP += $(call help,sh.mariadb,Shell environment - MariaDB)
sh.mariadb:
	docker-compose exec \
		mariadb \
		bash

endif

#######
# App #
#######

HELP += $(call help_section, App)
