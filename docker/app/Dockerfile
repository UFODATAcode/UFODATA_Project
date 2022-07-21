FROM composer:2.3 AS composer_img
FROM php:8.1-apache

# Composer
COPY --from=composer_img /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# php extensions
RUN apt-get update \
    && apt-get install -y \
      libpq-dev \
      git \
      libzip-dev \
      zip \
      libicu-dev \
      libcurl4-openssl-dev \
      pkg-config \
      libssl-dev\
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install zip \
    && docker-php-ext-enable zip \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./docker/app/apache.conf /etc/apache2/sites-enabled/ufodata.conf
COPY . /var/www/html/

WORKDIR /var/www/html/

RUN composer install
