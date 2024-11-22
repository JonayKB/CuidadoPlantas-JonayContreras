#!/bin/bash

# Esperar a MySQL
echo "Waiting for MySql..."
until mysqladmin ping -h "plantas-mysql-db" --silent; do
    echo "Waiting for MySql..."
    sleep 2
done

# Instalar dependencias de Composer
if [ ! -d "/var/www/vendor" ]; then
    echo "Installing Composer..."
    composer update
    composer install
fi

# Instalar dependencias de Node.js
if [ ! -d "/var/www/node_modules" ]; then
  echo "Installing Node.js..."
  npm install
fi

# Compilar Vite
if [ ! -f "/var/www/public/build/manifest.json" ]; then
  echo "Compiling Vite..."
  npm run build
fi

# Crear la base de datos SQLite
if [ ! -f "/var/www/database/database.sqlite" ]; then
    echo "Creating SQLite database..."
    touch /var/www/database/database.sqlite
    chmod 777 /var/www/database/database.sqlite
    chown www-data:www-data /var/www/database/database.sqlite
    php /var/www/artisan migrate --database sqliteLocal
fi
if [ ! -f "/usr/local/etc/php/conf.d/uploads.ini" ]; then
# Cambiar los valores de upload_max_filesize y post_max_size a 100M
echo "Setting upload_max_filesize and post_max_size to 100M"
echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini
echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini
fi

# Ejecutar migraciones
echo "Doing migrations..."
php /var/www/artisan migrate
php artisan key:generate

# Iniciar PHP-FPM
echo "Starting PHP-FPM..."
php-fpm
