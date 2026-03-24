#!/bin/bash
set -e

php artisan config:cache
php artisan migrate --force
php artisan db:seed --class=AdminSeeder --force 2>/dev/null || true

exec apache2-foreground