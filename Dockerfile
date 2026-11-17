
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libssl-dev \
    pkg-config \
    && docker-php-ext-install mysqli \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

EXPOSE 80
