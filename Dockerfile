FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
        libssl-dev \
        pkg-config \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install gd zip \
        && rm -rf /var/lib/apt/lists/*

# Instalar extensión MongoDB via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar configuración de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80