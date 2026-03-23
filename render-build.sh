#!/usr/bin/env bash
set -e

composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

**Step 6 — Buat file `Procfile`** kat root project:
```
web: vendor/bin/heroku-php-apache2 public/