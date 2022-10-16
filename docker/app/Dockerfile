FROM composer:2.4.2 AS composer_img
FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

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

RUN echo "upload_max_filesize=32M" >> "$PHP_INI_DIR/php.ini"
COPY ./docker/app/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./docker/app/entrypoint.sh /entrypoint.sh
COPY . /var/www/html/

WORKDIR /var/www/html/

CMD ["apache2-foreground"]

ENTRYPOINT ["/entrypoint.sh"]
