#!/bin/sh

cd /app || exit

# re-optimize again to factor in runtime configuration
php artisan optimize

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
