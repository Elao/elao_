########
# Base #
########

FROM debian:bullseye-slim

ARG DEBIAN_FRONTEND="noninteractive"

ARG MANALA_USER_ID="1000"
ARG MANALA_GROUP_ID="1000"

ARG GOSU_VERSION="1.16"
ARG GOMPLATE_VERSION="3.11.3"

# The 'container' environment variable tells systemd that it's running inside a
# Docker container environment.
# It's also internally used for checking we're running inside a container.
ENV container="docker"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

RUN \
    # Backports
    echo "deb http://deb.debian.org/debian $(. /etc/os-release; echo "${VERSION_CODENAME}")-backports main" > /etc/apt/sources.list.d/backports.list \
    && apt-get --quiet update \
    && apt-get --quiet --yes --purge --autoremove upgrade \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        s6 \
        sudo \
        curl \
        ca-certificates \
        gnupg \
        libarchive-tools bzip2 \
        bash-completion \
        rsync \
        git \
        make \
        less \
        vim \
        socat \
    # Apt keyrings (debian < bookworm)
    && mkdir --verbose --parents /etc/apt/keyrings \
    # User
    && addgroup --gid ${MANALA_GROUP_ID} lazy \
    && adduser --home /home/lazy --shell /bin/bash --uid ${MANALA_USER_ID} --gecos lazy --ingroup lazy --disabled-password lazy \
    && mkdir --verbose --parents /run/user/${MANALA_USER_ID} && chown lazy:lazy /run/user/${MANALA_USER_ID} \
    && echo "lazy ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/lazy \
    # Gosu
    && curl -sSL "https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu \
    # Gomplate
    && curl -sSL "https://github.com/hairyhenderson/gomplate/releases/download/v${GOMPLATE_VERSION}/gomplate_linux-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/gomplate \
    && chmod +x /usr/local/bin/gomplate \
    # Bash completion
    && mkdir --verbose --parents /etc/bash_completion.d \
    # Oh My Bash
    && git clone https://github.com/ohmybash/oh-my-bash.git /usr/local/share/oh-my-bash \
    # Clean
    && rm -rf /var/lib/apt/lists/*

##########
# System #
##########

RUN \
    apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        unzip \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Nginx
RUN \
    curl -sSL http://nginx.org/keys/nginx_signing.key \
        | gpg --dearmor --output /etc/apt/keyrings/nginx.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nginx.gpg] http://nginx.org/packages/debian/ $(. /etc/os-release; echo "${VERSION_CODENAME}") nginx" > /etc/apt/sources.list.d/nginx.list \
    && printf "Package: nginx\n\
Pin: version 1.20.*\n\
Pin-Priority: 1000\n\
" > /etc/apt/preferences.d/nginx \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        nginx=1.20.* \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Php
RUN \
    curl -sSL https://packages.sury.org/php/apt.gpg \
        --output /etc/apt/keyrings/sury.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/sury.gpg] https://packages.sury.org/php/ $(. /etc/os-release; echo "${VERSION_CODENAME}") main" > /etc/apt/sources.list.d/php.list \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
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
    && apt-get --quiet --yes --purge --autoremove upgrade \
    && phpdismod xdebug \
    && mkdir --verbose --parents /run/php \
    && update-alternatives --install /usr/sbin/php-fpm php-fpm /usr/sbin/php-fpm8.1 1 \
    && update-alternatives --install /etc/php/default php-config-default /etc/php/8.1 1 \
    # Composer
    && curl -sSL https://getcomposer.org/installer \
        | php -- --install-dir /usr/local/bin --filename composer \
    # Symfony
    && curl -sSL "https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac).tar.gz" \
        | bsdtar -xvf - -C /usr/local/bin symfony \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Nodejs
RUN \
    curl -sSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key \
        | gpg --dearmor --output /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_16.x $(. /etc/os-release; echo "${VERSION_CODENAME}") main" > /etc/apt/sources.list.d/nodejs.list \
    && printf "Package: nodejs\n\
Pin: origin deb.nodesource.com\n\
Pin-Priority: 1000\n\
" > /etc/apt/preferences.d/nodejs \
    && curl -sSL https://dl.yarnpkg.com/debian/pubkey.gpg \
        | gpg --dearmor --output /etc/apt/keyrings/yarn.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        nodejs \
        yarn \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Run
COPY docker/entrypoint.sh /
ENTRYPOINT ["/entrypoint.sh"]
