FROM php:8.2-apache-bookworm

RUN sed -i 's|http://deb.debian.org|https://deb.debian.org|g; s|http://security.debian.org|https://security.debian.org|g' /etc/apt/sources.list.d/debian.sources \
    && apt-get update \
    && apt-get install -y \
        libssl-dev \
        pkg-config \
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