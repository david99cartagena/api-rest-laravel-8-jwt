# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones necesarias para Laravel + PostgreSQL
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite (Laravel lo requiere)
RUN a2enmod rewrite

# Habilita mod_headers para permitir CORS desde Apache
RUN a2enmod headers

# Agrega encabezados CORS a todas las respuestas (manejo base desde Apache)
RUN echo '\n\
<IfModule mod_headers.c>\n\
    Header always set Access-Control-Allow-Origin "*"\n\
    Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"\n\
    Header always set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"\n\
</IfModule>\n\
' >> /etc/apache2/apache2.conf

# Establece directorio de trabajo antes de copiar
WORKDIR /var/www/html

# Copia Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala dependencias PHP (modo producción)
RUN composer install --optimize-autoloader --no-dev

# Fija permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Configura Apache para servir Laravel desde el directorio /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Asegura que el .htaccess funcione dentro de /public
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>" >> /etc/apache2/apache2.conf

# Expone el puerto 80
EXPOSE 80

# Ejecuta comandos Laravel y luego Apache
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan l5-swagger:generate && \
    apache2-foreground

# CMD php artisan config:clear && php artisan cache:clear && php artisan config:cache && apache2-foreground
# CMD php artisan optimize:clear && php artisan config:cache && apache2-foreground
