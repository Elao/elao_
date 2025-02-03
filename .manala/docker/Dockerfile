########
# Base #
########

FROM debian:bookworm-slim

ARG DEBIAN_FRONTEND="noninteractive"

ARG MANALA_USER_ID="1000"
ARG MANALA_GROUP_ID="1000"

ARG GOSU_VERSION="1.17"
ARG GOMPLATE_VERSION="4.2.0"
ARG DIRENV_VERSION="2.35.0"
ARG JQ_VERSION="1.7.1"
ARG YQ_VERSION="4.44.6"
ARG STARSHIP_VERSION="1.21.1"
ARG FZF_VERSION="0.56.3"

# The 'container' environment variable tells systemd that it's running inside a
# Docker container environment.
# It's also internally used for checking we're running inside a container.
ENV container="docker"

# Default locale
ENV LANG="C.UTF-8"

# Starship
ENV STARSHIP_CONFIG=/etc/starship/starship.toml

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

RUN \
    apt-get --quiet update \
    && apt-get --quiet --yes --purge --autoremove upgrade \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        bzip2 \
        ca-certificates \
        curl \
        git \
        gnupg \
        less \
        libarchive-tools \
        make \
        rsync \
        s6 \
        socat \
        sudo \
        vim \
        zsh \
    # User
    && addgroup --gid ${MANALA_GROUP_ID} lazy \
    && adduser --home /home/lazy --shell /bin/zsh --uid ${MANALA_USER_ID} --gecos lazy --ingroup lazy --disabled-password lazy \
    && install --verbose --mode 0755 --group lazy --owner lazy --directory /run/user/${MANALA_USER_ID} \
    && echo "lazy ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/lazy \
    # Gosu
    && curl -sSLf "https://github.com/tianon/gosu/releases/download/${GOSU_VERSION}/gosu-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/gosu \
    && chmod +x /usr/local/bin/gosu \
    # Gomplate
    && curl -sSLf "https://github.com/hairyhenderson/gomplate/releases/download/v${GOMPLATE_VERSION}/gomplate_linux-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/gomplate \
    && chmod +x /usr/local/bin/gomplate \
    # Direnv
    && curl -sSLf "https://github.com/direnv/direnv/releases/download/v${DIRENV_VERSION}/direnv.linux-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/direnv \
    && chmod +x /usr/local/bin/direnv \
    # Jq
    && curl -sSLf "https://github.com/jqlang/jq/releases/download/jq-${JQ_VERSION}/jq-linux-$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/jq \
    && chmod +x /usr/local/bin/jq \
    # Yq
    && curl -sSLf "https://github.com/mikefarah/yq/releases/download/v${YQ_VERSION}/yq_linux_$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac)" \
        --output /usr/local/bin/yq \
    && chmod +x /usr/local/bin/yq \
    # Oh My Zsh
    && git clone https://github.com/ohmyzsh/ohmyzsh.git /usr/local/share/oh-my-zsh \
    # See: https://github.com/ohmyzsh/ohmyzsh/issues/11995
    && git clone https://github.com/zsh-users/zsh-autosuggestions.git /usr/local/share/oh-my-zsh/custom/plugins/zsh-autosuggestions \
    && git clone https://github.com/zsh-users/zsh-syntax-highlighting.git /usr/local/share/oh-my-zsh/custom/plugins/zsh-syntax-highlighting \
    && echo "ZDOTDIR=/etc/zsh" > /etc/zsh/zshenv \
    # Starship
    && curl -sSLf "https://github.com/starship/starship/releases/download/v${STARSHIP_VERSION}/starship-$(case $(dpkg --print-architecture) in "amd64") echo "x86_64" ;; "arm64") echo "aarch64" ;; esac)-unknown-linux-musl.tar.gz" \
        | bsdtar -xvf - -C /usr/local/bin \
    && echo "Defaults env_keep += STARSHIP_CONFIG" > /etc/sudoers.d/starship \
    # Fzf
    && curl -sSLf "https://github.com/junegunn/fzf/releases/download/v${FZF_VERSION}/fzf-${FZF_VERSION}-linux_$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac).tar.gz" \
        | bsdtar -xvf - -C /usr/local/bin \
    && chmod +x /usr/local/bin/fzf \
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
    curl -sSLf http://nginx.org/keys/nginx_signing.key \
        --output /etc/apt/keyrings/nginx.asc \
    && printf "\
Types: deb\n\
URIs: http://nginx.org/packages/debian\n\
Suites: $(. /etc/os-release; echo "${VERSION_CODENAME}")\n\
Components: nginx\n\
Signed-By: /etc/apt/keyrings/nginx.asc\n\
" > /etc/apt/sources.list.d/nginx.sources \
    && printf "\
Package: nginx\n\
Pin: version 1.24.*\n\
Pin-Priority: 1000\n\
" > /etc/apt/preferences.d/nginx \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        nginx=1.24.* \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Php
RUN \
    curl -sSLf https://packages.sury.org/php/apt.gpg \
        --output /etc/apt/keyrings/sury_php.gpg \
    && printf "\
Types: deb\n\
URIs: https://packages.sury.org/php/\n\
Suites: $(. /etc/os-release; echo "${VERSION_CODENAME}")\n\
Components: main\n\
Signed-By: /etc/apt/keyrings/sury_php.gpg\n\
" > /etc/apt/sources.list.d/sury_php.sources \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        php8.3-cli \
        php8.3-fpm \
        php8.3-opcache \
        php8.3-xdebug \
        # Symfony
        php8.3-curl \
        php8.3-intl \
        php8.3-mbstring \
        php8.3-xml \
        # Project
        php8.3-gd \
    && apt-get --quiet --yes --purge --autoremove upgrade \
    && phpdismod xdebug \
    && install --verbose --mode 0755 --directory /run/php \
    && update-alternatives --install /usr/sbin/php-fpm php-fpm /usr/sbin/php-fpm8.3 1 \
    && update-alternatives --install /etc/php/default php-config-default /etc/php/8.3 1 \
    # Composer
    && curl -sSLf https://getcomposer.org/installer \
        | php -- --install-dir /usr/local/bin --filename composer \
    # Symfony
    && curl -sSLf "https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_$(case $(dpkg --print-architecture) in "amd64") echo "amd64" ;; "arm64") echo "arm64" ;; esac).tar.gz" \
        | bsdtar -xvf - -C /usr/local/bin symfony \
    # Oh My Zsh plugin
    # See: https://github.com/ohmyzsh/ohmyzsh/pull/12768
    && mkdir /usr/local/share/oh-my-zsh/custom/plugins/symfony-cli \
    && printf "\
if [[ ! -f \"\$ZSH_CACHE_DIR/completions/_symfony-cli\" ]]; then\n\
  typeset -g -A _comps\n\
  autoload -Uz _symfony-cli\n\
  _comps[symfony]=_symfony-cli\n\
fi\n\
symfony self:completion zsh >| \"\$ZSH_CACHE_DIR/completions/_symfony-cli\" &|\n\
" > /usr/local/share/oh-my-zsh/custom/plugins/symfony-cli/symfony-cli.plugin.zsh \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Nodejs
RUN \
    curl -sSLf https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
        --output /etc/apt/keyrings/nodesource.asc \
    && printf "\
Types: deb\n\
URIs: https://deb.nodesource.com/node_22.x\n\
Suites: nodistro\n\
Components: main\n\
Signed-By: /etc/apt/keyrings/nodesource.asc\n\
" > /etc/apt/sources.list.d/nodesource.sources \
    && printf "\
Package: nodejs\n\
Pin: origin deb.nodesource.com\n\
Pin-Priority: 1000\n\
" > /etc/apt/preferences.d/nodejs \
    && curl -sSLf https://dl.yarnpkg.com/debian/pubkey.gpg \
        --output /etc/apt/keyrings/yarn.asc \
    && printf "\
Types: deb\n\
URIs:  https://dl.yarnpkg.com/debian/\n\
Suites: stable\n\
Components: main\n\
Signed-By: /etc/apt/keyrings/yarn.asc\n\
" > /etc/apt/sources.list.d/yarn.sources \
    && apt-get --quiet update \
    && apt-get --quiet --yes --no-install-recommends --verbose-versions install \
        nodejs \
        yarn \
    # Clean
    && rm -rf /var/lib/apt/lists/*

# Run
COPY docker/entrypoint.sh /
ENTRYPOINT ["/entrypoint.sh"]
