FROM php:8.1-fpm

MAINTAINER Grzegorz Gabryel <greg@grzegab.eu>

RUN apt-get update && apt-get install -y libpq-dev libzip-dev zlib1g-dev unzip libicu-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install pcov

RUN docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql zip intl opcache
RUN docker-php-ext-enable opcache pcov

# Override with custom opcache settings
COPY php-additional.ini $PHP_INI_DIR/conf.d/

#Install composer form docker
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /data/www

COPY entrypoint.sh /
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]