FROM php:8.5-apache

RUN apt-get update \
 && apt-get install -y \
    ca-certificates \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
 && update-ca-certificates \
 && docker-php-ext-install zip \
 && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/default.conf /etc/apache2/sites-available/000-default.conf