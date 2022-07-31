# Global settings
ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.17

FROM php:${PHP_VERSION}-fpm-alpine3.15 AS rlt_php

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        file \
        gettext \
        git \
        mariadb-client \
    ;
ENV ALPINE_VERSION=3.15
ENV MONGODB_VERSION=1.6.1
# Install & clean up dependencies
RUN apk --no-cache --update --repository http://dl-cdn.alpinelinux.org/alpine/v$ALPINE_VERSION/main/ add \
    autoconf \
    build-base \
    ca-certificates \
&& apk --no-cache --update --repository http://dl-3.alpinelinux.org/alpine/v3.15/main/ add \
    curl \
    openssl \
    openssl-dev \
    libtool \
    icu \
    icu-libs \
    icu-dev \
    libwebp \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libxml2-dev \
&& apk --no-cache --update --repository http://dl-3.alpinelinux.org/alpine/v3.15/community/ add \
    php8-gd \
    php8-sockets \
    php8-zlib \
    php8-intl \
    php8-opcache \
    php8-bcmath \
&& docker-php-ext-configure intl \
&& docker-php-ext-install \
    pdo_mysql \
    sockets \
    gd \
    intl \
    opcache \
    bcmath \
    soap \
&& apk --no-cache del \
    wget \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    tar \
    autoconf \
    build-base \
    libtool \
&& rm -rf /var/cache/apk/* /tmp/*

#RUN apk --no-cache --update --repository http://dl-3.alpinelinux.org/alpine/v3.5/main/ add \
#RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
#RUN apk add nodejs
# RUN apk add --update nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY docker/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/php-cli.ini /usr/local/etc/php/php-cli.ini
COPY docker/php-fpm/zz-docker.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
# ENV COMPOSER_ALLOW_SUPERUSER=1
# ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/application

# build for production
#ARG APP_ENV=production

# copy everything, excluding the one from .dockerignore file
COPY . ./

# RUN set -eux; \
#     mkdir -p storage/logs storage/framework bootstrap/cache; \
#     composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader; \
#     composer clear-cache

COPY docker/php-fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]

# NGINX
FROM nginx:${NGINX_VERSION}-alpine AS rlt_nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

WORKDIR /var/www/application

COPY --from=rlt_php /var/www/application/public public/
