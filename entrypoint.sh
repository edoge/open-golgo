#!/bin/sh

printenv | awk '{print "export " $1}' > /root/env.sh

php artisan boot:repair

supervisord -c /etc/supervisor.conf
