##########
# Docker #
##########

# Internal usage:
#   $(_docker) [COMMAND] [ARGS...]

ifneq ($(container),docker)
define _docker
	docker
endef
else
define _docker
	$(call message_error, Unable to execute command inside a docker container) ; \
		exit 1 ;
endef
endif

##################
# Docker Compose #
##################

# Internal usage:
#   $(_docker_compose) [COMMAND] [ARGS...]

ifneq ($(container),docker)
define _docker_compose
	$(_DOCKER_COMPOSE_ENV) \
	docker-compose \
		$(if $(_DOCKER_COMPOSE_PROJECT_NAME),--project-name $(_DOCKER_COMPOSE_PROJECT_NAME)) \
		$(foreach file, $(_DOCKER_COMPOSE_FILE), \
			--file $(file) \
		)
endef
else
define _docker_compose
	$(call message_error, Unable to execute command inside a docker container) ; \
		exit 1 ;
endef
endif

###########
# Helpers #
###########

# Usage:
#   $(call docker_run, COMMAND [ARGS...])
#   $(call docker_run, OPTIONS, COMMAND [ARGS...])

ifneq ($(container),docker)
define docker_run
	$(_docker_compose) run \
		--rm \
		$(if $(strip $(2)), \
			$(strip $(1)) $(_DOCKER_COMPOSE_SERVICE) sh -c '$(strip $(2))', \
			$(_DOCKER_COMPOSE_SERVICE) sh -c '$(subst ','\'',$(strip $(1)))' \
		)
endef
else
define docker_run
	$(if $(strip $(2)), \
		$(call message_error, Unable to execute command inside a docker container) ; exit 1 ;, \
		$(strip $(1)) \
	)
endef
endif

# Usage:
#   $(docker_exec) [COMMAND] [ARGS...]

ifneq ($(container),docker)
define docker_exec
	$(_docker_compose) exec \
		$(if $(_DOCKER_COMPOSE_USER),--user $(_DOCKER_COMPOSE_USER)) \
		$(_DOCKER_COMPOSE_SERVICE) \
		sh -c '$(strip $(1))'
endef
else
define docker_exec
	$(strip $(1))
endef
endif
