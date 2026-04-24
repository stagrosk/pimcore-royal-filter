FROM opendxp/opendxp:php8.3-supervisord-latest

# Install pdo_mysql extension
RUN docker-php-ext-install pdo_mysql

# Install ACL utilities for file permission management
RUN rm -f /etc/apt/sources.list.d/mysql.list && apt-get update && apt-get install -y acl && rm -rf /var/lib/apt/lists/*

# Set default ACL on asset directories so www-data can always read/write/delete
COPY .docker/php/entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["php-fpm"]
