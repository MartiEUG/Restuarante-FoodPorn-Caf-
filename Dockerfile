FROM php:8.2-apache

# Instalar extensiones necesarias de PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar herramientas adicionales
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite headers

# Copiar configuración de Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Configurar DocumentRoot
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Copiar archivos de la aplicación
COPY . /var/www/html/

# Crear directorios necesarios
RUN mkdir -p /var/www/html/logs && \
    mkdir -p /var/www/html/public/uploads

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 777 /var/www/html/logs && \
    chmod -R 777 /var/www/html/public/uploads

WORKDIR /var/www/html

EXPOSE 80
