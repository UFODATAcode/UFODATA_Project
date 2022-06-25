FROM composer:2.3 AS composer_img
FROM php:8.1-apache

# Composer
COPY --from=composer_img /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

# php extensions
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable pdo_pgsql

COPY . /var/www/html/

WORKDIR /var/www/html/

RUN composer install
