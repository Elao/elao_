ARG DEBIAN=bookworm

FROM debian:${DEBIAN}-slim as website

ARG DEBIAN
ARG NGINX_VERSION=1.26
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg2 \
        sudo

# User
RUN addgroup --gid ${GROUP_ID} app \
    && adduser --home /home/app --shell /bin/bash --uid ${USER_ID} --gecos app --ingroup app --disabled-password app \
    && echo "app ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/app \
    # App dir
    && mkdir --parents /srv && chown app:app /srv

#########
# Nginx #
#########
RUN \
    echo "deb http://nginx.org/packages/debian/ ${DEBIAN} nginx" > /etc/apt/sources.list.d/nginx.list \
    && curl -sSL http://nginx.org/keys/nginx_signing.key \
    | apt-key add - \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx=${NGINX_VERSION}.* \
    && chown -R $USER_ID:0 /etc/nginx /var/log/nginx \
    && chmod -R g+w /etc/nginx

#################
# Configuration #
#################
COPY --chown=app:app kubernetes/app.conf /etc/nginx/app.conf
COPY --chown=app:app build/ /srv/app/website/

#########
# Clean #
#########
RUN \
    rm -rf \
        /var/lib/apt/lists/* \
        /var/cache/debconf/*-old \
        /var/lib/dpkg/*-old \
    && truncate -s 0 /var/log/*.log

CMD ["nginx", "-c", "/etc/nginx/app.conf"]
