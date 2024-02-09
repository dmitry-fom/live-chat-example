#!/bin/sh

composer install

php artisan migrate

php-fpm