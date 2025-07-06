# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite (Laravel lo requiere)
RUN a2enmod rewrite

# Establece directorio de trabajo antes de copiar
WORKDIR /var/www/html

# Copia Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala dependencias PHP
RUN composer install --optimize-autoloader --no-dev

# Fija permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Configura Apache para servir Laravel desde el directorio /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Asegura que el .htaccess funcione
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    </Directory>" >> /etc/apache2/apache2.conf

# Expone el puerto 80
EXPOSE 80

# Inicia Apache en foreground
CMD ["apache2-foreground"]