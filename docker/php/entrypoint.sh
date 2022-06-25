#!/bin/bash

composer install --no-scripts --no-interaction
php-fpm -F

exec docker-php-entrypoint $@