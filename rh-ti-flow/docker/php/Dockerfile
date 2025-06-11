FROM php:8.2-fpm

# Instalar dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Composer (global)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
