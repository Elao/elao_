ARG DEBIAN=buster

########
# Base #
########

FROM debian:${DEBIAN}-slim as base

ARG DEBIAN
ARG USER_ID=1000
ARG GROUP_ID=1000
ARG S6_OVERLAY_VERSION=2.2.0.3
ARG GOSU_VERSION=1.13
ARG GOMPLATE_VERSION=3.9.0
ARG STARSHIP_VERSION=0.55.0

# The 'container' environment variable tells systemd that it's running inside a
# Docker container environment.
# It's also internally used for checking we're running inside a container.
ENV container="docker"

RUN \
    apt-get update \
    && apt-get install --yes --no-install-recommends \
        sudo \
        curl \
        ca-certificates \
        gnupg \
        bsdtar bzip2 \
        bash-completion \
        make \
        less \
        vim \
    # User
    && addgroup --gid ${GROUP_ID} lazy \
    && adduser --home /home/lazy --shell /bin/bash --uid ${USER_ID} --gecos lazy --ingroup lazy --disabled-password lazy \
    && mkdir -p /run/user/${USER_ID} && chown lazy:lazy /run/user/${USER_ID} \
    && printf "lazy ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/lazy \
    # S6
    && curl -sSL https://github.com/just-containers/s6-overlay/releases/download/v${S6_OVERLAY_VERSION}/s6-overlay-amd64.tar.gz \
      | bsdtar -xvf - -C /usr/bin --strip-components=2 bin/s6-svscan bin/s6-supervise bin/s6-svc \
    # Gosu
    && curl -sSL https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-amd64 \
        -o /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu \
    # Gomplate
    && curl -sSL https://github.com/hairyhenderson/gomplate/releases/download/v${GOMPLATE_VERSION}/gomplate_linux-amd64 \
        -o /usr/local/bin/gomplate \
    && chmod +x /usr/local/bin/gomplate \
    # Bash completion
    && mkdir -p /etc/bash_completion.d \
    # Starship
    && curl -sSL https://github.com/starship/starship/releases/download/v${STARSHIP_VERSION}/starship-x86_64-unknown-linux-gnu.tar.gz \
        | bsdtar -xvf - -C /usr/local/bin \
    && printf "eval \"\$(starship init bash)\"\nexport STARSHIP_CONFIG=\"/etc/starship/config.toml\"" > /etc/profile.d/starship.sh

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
        nginx=1.18.*

# Php
RUN \
    printf "deb https://packages.sury.org/php/ ${DEBIAN} main" > /etc/apt/sources.list.d/php.list \
    && curl -sSL https://packages.sury.org/php/apt.gpg \
        -o /etc/apt/trusted.gpg.d/php.gpg \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        php7.4-cli \
        php7.4-fpm \
        php7.4-opcache \
        php7.4-xdebug \
        # Symfony
        php7.4-intl \
        php7.4-curl \
        php7.4-mbstring \
        php7.4-xml \
        # Project
        php7.4-gd \
    && phpdismod xdebug \
    && mkdir /run/php \
    && update-alternatives --install /usr/sbin/php-fpm php-fpm /usr/sbin/php-fpm7.4 1 \
    && update-alternatives --install /etc/php/default php-config-default /etc/php/7.4 1 \
    # Composer
    && curl -sSL https://getcomposer.org/installer \
        | php -- --install-dir /usr/local/bin --filename composer \
    # Symfony
    && SYMFONY_VERSION=`curl -sSL https://get.symfony.com/cli/LATEST --fail` \
    && curl -sSL https://github.com/symfony/cli/releases/download/v${SYMFONY_VERSION}/symfony_linux_amd64 \
        -o /usr/local/bin/symfony \
    && chmod +x /usr/local/bin/symfony

# Nodejs
RUN \
    printf "deb https://deb.nodesource.com/node_12.x ${DEBIAN} main" > /etc/apt/sources.list.d/node.list \
    && curl -sSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key \
        | apt-key add - \
    && printf "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sSL https://dl.yarnpkg.com/debian/pubkey.gpg \
        | apt-key add - \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        nodejs \
        yarn

