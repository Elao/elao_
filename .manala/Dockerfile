ARG NGINX_VERSION=1.18
ARG MAILDEV_VERSION=2.0.0-beta3

#########
# Nginx #
#########

FROM nginx:${NGINX_VERSION}-alpine AS nginx

###########
# MailDev #
###########

FROM maildev/maildev:${MAILDEV_VERSION} AS maildev

USER root

CMD ["--web", "80", "--smtp", "25"]

########
# Base #
########

FROM debian:buster-slim as base

# Disable irrelevants apt-key warnings
ARG APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1
# Disable all debian user interaction
ARG DEBIAN_FRONTEND=noninteractive

# The 'container' environment variable tells systemd that it's running inside a
# Docker container environment.
# It's also internally used for checking we're running inside a container.
ENV container="docker"

RUN \
    apt-get update \
    && apt-get install --yes --no-install-recommends \
        bash-completion \
        apt-utils \
        gnupg \
        ca-certificates \
        sudo \
        curl \
        unzip \
        make \
        rsync \
        vim-tiny \
        less \
    # User
    && adduser --disabled-password --gecos "" app \
    && echo "app ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/app \
    # Starship
    && curl --location https://github.com/starship/starship/releases/latest/download/starship-x86_64-unknown-linux-gnu.tar.gz \
        | tar zx -C /usr/local/bin \
    && echo "eval \"\$(starship init bash)\"" > /etc/profile.d/starship.sh

WORKDIR /srv

#######
# App #
#######

FROM base AS app

ARG PHP_VERSION=7.4

# Disable irrelevants apt-key warnings
ARG APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1
# Disable all debian user interaction
ARG DEBIAN_FRONTEND=noninteractive

ARG NODE_VERSION=12

RUN \
    echo "deb https://deb.nodesource.com/node_${NODE_VERSION}.x buster main" > /etc/apt/sources.list.d/node.list \
    && curl --silent --location --show-error https://deb.nodesource.com/gpgkey/nodesource.gpg.key \
        | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl --silent --location --show-error https://dl.yarnpkg.com/debian/pubkey.gpg \
        | apt-key add - \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        nodejs \
        yarn \
    # Cache
    && mkdir -p /usr/etc \
    && echo "cache=\${XDG_CACHE_HOME}/npm" > /usr/etc/npmrc

# Php
RUN \
    echo "deb https://packages.sury.org/php/ buster main" > /etc/apt/sources.list.d/php.list \
    && curl --silent --location --show-error https://packages.sury.org/php/apt.gpg \
        --output /etc/apt/trusted.gpg.d/php.gpg \
    && apt-get update \
    && apt-get install --yes --no-install-recommends \
        php${PHP_VERSION}-cli \
        php${PHP_VERSION}-fpm \
        php${PHP_VERSION}-intl \
        php${PHP_VERSION}-curl \
        php${PHP_VERSION}-mbstring \
        php${PHP_VERSION}-xml \
        php${PHP_VERSION}-sqlite \
        php-xdebug \
    && mkdir /run/php \
    # Composer
    && curl --silent --location --show-error https://getcomposer.org/installer \
        | php -- --install-dir /usr/local/bin --filename composer \
    # Symfony
    && VERSION=`curl --silent --location --fail https://get.symfony.com/cli/LATEST` \
    && curl --silent --location --show-error https://github.com/symfony/cli/releases/download/v${VERSION}/symfony_linux_amd64.gz \
        --output - | gunzip > /usr/local/bin/symfony \
    && chmod +x /usr/local/bin/symfony

# Override stop signal to stop process gracefully
# https://github.com/php/php-src/blob/17baa87faddc2550def3ae7314236826bc1b1398/sapi/fpm/php-fpm.8.in#L163
STOPSIGNAL SIGQUIT

EXPOSE 9000

CMD ["php-fpm7.4"]
