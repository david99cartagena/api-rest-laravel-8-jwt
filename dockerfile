# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip

# Habilita Apache mod_rewrite (requerido por Laravel)
RUN a2enmod rewrite

# Copia archivos del proyecto al contenedor
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Fija permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configura Apache (usa public como raíz del sitio)
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80 (usado por Apache)
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
