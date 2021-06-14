#!/bin/bash

set -e

# Fix docker for mac SSH_AUTH_SOCK permission
if [[ ${SSH_AUTH_SOCK} == "/run/host-services/ssh-auth.sock" && -S ${SSH_AUTH_SOCK} ]]; then
    chmod 777 ${SSH_AUTH_SOCK}
fi

# As a consequence of running the container as root user,
# tty is not writable by sued user
if [[ -t 1 ]] ; then
  chmod 666 $(tty)
  export GPG_TTY=$(tty)
fi

# Home cache
HOME_DIR=${CACHE_DIR}/home
if [[ ! -d ${HOME_DIR} ]]; then
    cp --archive /home/lazy/. ${HOME_DIR}
fi
usermod --home ${HOME_DIR} lazy 2>/dev/null

# Templates
GOMPLATE_LOG_FORMAT=simple gomplate --input-dir=.manala/templates --output-dir=/etc 2>/dev/null

# Services
if [[ -z $@ ]] && [[ -d /etc/services ]]; then
    exec s6-svscan /etc/services
fi

# Command
exec gosu lazy "$@"
