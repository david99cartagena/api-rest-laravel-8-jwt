#!/bin/bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
exec apache2-foreground
