FROM opendxp/opendxp:php8.3-debug-latest

# Install ACL utilities for file permission management (pdo_mysql is already in the OpenDXP base image)
RUN apt-get update && apt-get install -y --no-install-recommends acl && rm -rf /var/lib/apt/lists/*

# Set default ACL on asset directories so www-data can always read/write/delete
COPY .docker/php/entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/custom-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["php-fpm"]
