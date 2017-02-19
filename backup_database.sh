#!/bin/sh
. /root/env.sh
php /var/www/html/artisan boot:crawl
php /var/www/html/artisan boot:backup
