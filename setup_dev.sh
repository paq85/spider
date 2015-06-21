#!/bin/bash

if [ ! -f composer.phar ]; then
    curl -sS https://getcomposer.org/installer | php
fi
php composer.phar self-update --no-interaction
php composer.phar install --optimize-autoloader --no-interaction