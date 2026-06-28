FROM php:8.2-apache-bookworm

# Cambiar repositorios a HTTPS (más seguro y puede evitar algunos bloqueos)
RUN sed -i 's/http:/https:/g' /etc/apt/sources.list.d/debian.sources 2>/dev/null || true && \
    sed -i 's/http:/https:/g' /etc/apt/sources.list 2>/dev/null || true

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

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar configuración de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Establecer directorio de trabajo
WORKDIR /var/www/html

EXPOSE 80