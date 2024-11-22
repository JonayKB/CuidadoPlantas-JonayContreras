#!/bin/bash

# Wait mysql
echo "Waiting for MySql..."
until mysqladmin ping -h "plantas-mysql-db" --silent; do
    echo "Waiting for MySql..."
    sleep 2
done

# Composer Dependencies
if [ ! -d "/var/www/vendor" ]; then
    echo "Installing Composer..."
    composer update
    composer install
fi
# Node dependencies
if [ ! -d "/var/www/node_modules" ]; then
  echo "Installing Node.js..."
  npm install
fi

# Compile Vite
if [ ! -f "/var/www/public/build/manifest.json" ]; then
  echo "Compiling Vite..."
  npm run build

fi
# Create sqlite Database
if [ ! -f "/var/www/database/database.sqlite" ]; then
    echo "Creating SQLite database..."
    touch /var/www/database/database.sqlite
    chmod 777 /var/www/database/database.sqlite
    chown www-data:www-data /var/www/database/database.sqlite
    php /var/www/artisan migrate --database sqliteLocal
fi


# Execute Migrations
echo "Doing migrations..."
php /var/www/artisan migrate
php artisan key:generate

# Start PHP-FPM server
echo "Starting PHP-FPM..."
php-fpm
