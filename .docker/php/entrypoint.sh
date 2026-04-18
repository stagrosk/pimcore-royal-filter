#!/bin/bash
set -e

# Set umask so new files/dirs get group+other write permission
# This ensures ACL mask includes write, making default ACLs effective
umask 002

# Ensure asset directories exist
mkdir -p /var/www/html/public/var/assets
mkdir -p /var/www/html/public/var/tmp
mkdir -p /var/www/html/var/tmp
mkdir -p /var/www/html/var/cache
mkdir -p /var/www/html/var/log

# Set default ACLs so www-data (uid 33) can read/write/delete all files
setfacl -R -m u:33:rwX /var/www/html/public/var/
setfacl -R -d -m u:33:rwX /var/www/html/public/var/
setfacl -R -m u:33:rwX /var/www/html/var/tmp/
setfacl -R -d -m u:33:rwX /var/www/html/var/tmp/
setfacl -R -m u:33:rwX /var/www/html/var/cache/
setfacl -R -d -m u:33:rwX /var/www/html/var/cache/
setfacl -R -m u:33:rwX /var/www/html/var/log/
setfacl -R -d -m u:33:rwX /var/www/html/var/log/

exec docker-php-entrypoint "$@"
