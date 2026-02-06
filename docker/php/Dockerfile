FROM php:8.4-apache

RUN apt-get update

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/default.conf /etc/apache2/sites-available/000-default.conf