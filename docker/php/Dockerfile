FROM php:8.1.6-fpm-alpine3.14

#intl için icu-dev gereklidir
#libzip-dev zip/unzip için gerekli C kütüphaneleridir.
#opcache önceden derlenmiş olan php betiğini cacheleyip tekrar çağrıldığında hızlı cevap vermesini sağlayan teknolojidir.

RUN apk update \
    && apk add --no-cache \
    bash \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    && docker-php-ext-configure  \
    intl \
    && docker-php-ext-install \
    intl \
    opcache \
    mysqli \
    pdo \
    pdo_mysql \
    && docker-php-ext-enable \
    pdo_mysql
