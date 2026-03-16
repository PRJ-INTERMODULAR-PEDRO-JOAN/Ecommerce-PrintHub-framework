#!/bin/sh
set -e

echo "==> PrintHub: starting container..."

# Set defaults for optional environment variables consumed by Supervisor
export QUEUE_MAX_TIME="${QUEUE_MAX_TIME:-3600}"

# Run database migrations (idempotent)
echo "==> Running database migrations..."
php artisan migrate --force

# Optimize the framework for production
echo "==> Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Fix storage permissions (needed when /storage is a named volume)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Starting Supervisor (Nginx + PHP-FPM + Queue)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
