#!/bin/bash

# Запускаем Supervisor в фоновом режиме
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf &

# Запускаем PHP-FPM
php-fpm
