FROM php:8.5-apache

RUN apt-get update \
 && apt-get install -y \
    ca-certificates \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
 && docker-php-ext-install intl \
 && update-ca-certificates \
 && docker-php-ext-install zip \
 && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN docker-php-ext-install pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/default.conf /etc/apache2/sites-available/000-default.conf