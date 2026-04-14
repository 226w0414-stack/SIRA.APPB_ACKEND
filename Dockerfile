# Usamos la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos soporte para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Instalamos y habilitamos la extensión mysqli para conectar con tu BD
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copiamos todos tus archivos PHP al directorio de Apache
COPY . /var/www/html/

# Ajustamos permisos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html/

# Exponemos el puerto 80
EXPOSE 80