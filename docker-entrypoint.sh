#!/bin/sh
set -e
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi
php artisan migrate --force --no-interaction 2>/dev/null || true
exec php artisan serve --host=0.0.0.0 --port=8000
