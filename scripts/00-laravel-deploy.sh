#!/usr/bin/env bash
echo "Running composer"
# composer update
composer install
# composer install --no-dev --working-dir=/var/www/html/
# composer global require hirak/prestissimo

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
