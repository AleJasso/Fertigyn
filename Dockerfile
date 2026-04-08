# Imagen base: PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite para que funcionen las rutas de Laravel
RUN a2enmod rewrite

# Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiar todo el proyecto al contenedor
COPY . /var/www/html

# Configurar el VirtualHost para que apunte a /public
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache
