#!/bin/sh
chmod 777 -R /var/www/html/var/cache
php-fpm -F -R #fix exited with code 0