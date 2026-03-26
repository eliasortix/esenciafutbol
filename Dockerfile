FROM php:8.2-fpm

# Instalar dependencias del sistema para PHP y Nginx
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libzip-dev unzip nginx libpq-dev

# Instalar extensiones de PHP necesarias (MySQL o Postgres)
RUN docker-php-ext-install pdo_mysql pdo_pgsql gd zip

# Copiar el código del proyecto al contenedor
COPY . /var/www/html

# Instalar Composer para las dependencias de Laravel
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN cd /var/www/html && composer install --no-dev --optimize-autoloader

# Dar permisos a las carpetas de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-available/default

# Puerto que usará Render
EXPOSE 80

# Comando para arrancar el servidor
CMD service nginx start && php-fpm