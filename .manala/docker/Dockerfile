ARG DEBIAN=bullseye

########
# Base #
########

FROM debian:${DEBIAN}-slim as base

ARG DEBIAN
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG GOSU_VERSION=1.14
ARG GOMPLATE_VERSION=3.11.1

# The 'container' environment variable tells systemd that it's running inside a
# Docker container environment.
# It's also internally used for checking we're running inside a container.
ENV container="docker"

RUN \
    # Backports
    printf "deb http://deb.debian.org/debian ${DEBIAN}-backports main" > /etc/apt/sources.list.d/backports.list \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        s6 \
        sudo \
        curl \
        ca-certificates \
        gnupg \
        libarchive-tools bzip2 \
        bash-completion \
        git \
        make \
        less \
        vim \
        socat \
    # User
    && addgroup --gid ${GROUP_ID} lazy \
    && adduser --home /home/lazy --shell /bin/bash --uid ${USER_ID} --gecos lazy --ingroup lazy --disabled-password lazy \
    && mkdir -p /run/user/${USER_ID} && chown lazy:lazy /run/user/${USER_ID} \
    && printf "lazy ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/lazy \
    # Gosu
    && curl -sSL https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac) \
        -o /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu \
    # Gomplate
    && curl -sSL https://github.com/hairyhenderson/gomplate/releases/download/v${GOMPLATE_VERSION}/gomplate_linux-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac) \
        -o /usr/local/bin/gomplate \
    && chmod +x /usr/local/bin/gomplate \
    # Bash completion
    && mkdir -p /etc/bash_completion.d \
    # Oh My Bash
    && git clone https://github.com/ohmybash/oh-my-bash.git /usr/local/share/oh-my-bash

##########
# System #
##########

FROM base AS system

ARG DEBIAN

RUN \
    apt-get install --yes --no-install-recommends \
      unzip \
      git \
      rsync

# Nginx
RUN \
    printf "deb http://nginx.org/packages/debian/ ${DEBIAN} nginx" > /etc/apt/sources.list.d/nginx.list \
    && curl -sSL http://nginx.org/keys/nginx_signing.key \
        | apt-key add - \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        nginx=1.20.*

# Php
RUN \
    printf "deb https://packages.sury.org/php/ ${DEBIAN} main" > /etc/apt/sources.list.d/php.list \
    && curl -sSL https://packages.sury.org/php/apt.gpg \
        -o /etc/apt/trusted.gpg.d/php.gpg \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        php8.1-cli \
        php8.1-fpm \
        php8.1-opcache \
        php8.1-xdebug \
        # Symfony
        php8.1-intl \
        php8.1-curl \
        php8.1-mbstring \
        php8.1-xml \
        # Project
        php8.1-gd \
    && phpdismod xdebug \
    && mkdir /run/php \
    && update-alternatives --install /usr/sbin/php-fpm php-fpm /usr/sbin/php-fpm8.1 1 \
    && update-alternatives --install /etc/php/default php-config-default /etc/php/8.1 1 \
    # Composer
    && curl -sSL https://getcomposer.org/installer \
        | php -- --install-dir /usr/local/bin --filename composer \
    # Symfony
    && curl -sSL https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac).tar.gz \
        | bsdtar -xvf - -C /usr/local/bin symfony

# Nodejs
RUN \
    printf "deb https://deb.nodesource.com/node_16.x ${DEBIAN} main" > /etc/apt/sources.list.d/node.list \
    && curl -sSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key \
        | apt-key add - \
    && printf "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sSL https://dl.yarnpkg.com/debian/pubkey.gpg \
        | apt-key add - \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        nodejs \
        yarn

