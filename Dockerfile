FROM php:8.2-apache

# Instalar extensiones necesarias para MongoDB
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean

# Habilitar mod_rewrite para URLs limpias
RUN a2enmod rewrite

# Configurar Apache para hot-reload
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar configuración de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# El código se monta como volumen para hot-reload
