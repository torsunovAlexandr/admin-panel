FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80
