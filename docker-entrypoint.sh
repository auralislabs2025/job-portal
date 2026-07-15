#!/bin/sh
set -e

if [ ! -f /var/www/storage/logs/.gitkeep ]; then
    mkdir -p /var/www/storage/framework/sessions
    mkdir -p /var/www/storage/framework/views
    mkdir -p /var/www/storage/framework/cache
    mkdir -p /var/www/storage/logs
    touch /var/www/storage/logs/.gitkeep
fi

php artisan storage:link --force 2>/dev/null || true
php artisan config:cache 2>/dev/null || true

php artisan migrate --force 2>/dev/null || echo "Migration postponed (DB not ready yet)"

exec "$@"
