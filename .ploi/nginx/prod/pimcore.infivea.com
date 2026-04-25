# /etc/nginx/sites-available/pimcore.infivea.com
#
# Legacy Pimcore 11 production vhost (php8.2-fpm). Will be retired once we
# cut over to OpenDXP at pim.infivea.com.
#
# IMPORTANT: this file pairs with two other configs that Ploi keeps in
# separate locations:
#   /etc/nginx/ssl/pimcore.infivea.com                                   ← see ./ssl-redirect.conf
#   /etc/nginx/ploi/pimcore.infivea.com/server/disable-basic-auth-well-known.conf
#                                                                        ← see ./disable-basic-auth-well-known.conf

# Ploi Webserver Configuration, do not remove!
include /etc/nginx/ploi/pimcore.infivea.com/before/*;

upstream php-pimcore10 {
    server unix:/run/php/php8.2-fpm.sock;
}

map $args $static_page_root {
    default                                 /var/tmp/pages;
    "~*(^|&)pimcore_editmode=true(&|$)"     /var/nonexistent;
    "~*(^|&)pimcore_preview=true(&|$)"      /var/nonexistent;
    "~*(^|&)pimcore_version=[^&]+(&|$)"     /var/nonexistent;
}

map $uri $static_page_uri {
    default                                 $uri;
    "/"                                     /%home;
}

# /etc/nginx/ssl/pimcore.infivea.com  ⇢ HTTP→HTTPS + www→non-www redirect blocks
# Has to live OUTSIDE any `server { }` block (it defines its own server blocks).
include /etc/nginx/ssl/pimcore.infivea.com;

# ─── Tailnet-only admin access (Tailscale Serve: tailnet:443 → localhost:8081) ───
server {
    listen 8081;
    server_name stagro-s1.tail27695b.ts.net;

    root /home/ploi/pimcore.infivea.com/public;
    index index.php;
    charset utf-8;
    client_max_body_size 100m;

    access_log /var/log/nginx/pimcore.infivea.com-tailnet-access.log;
    error_log  /var/log/nginx/pimcore.infivea.com-tailnet-error.log error;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    error_page 404 /index.php;

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~* /var/assets/.*\.php(/|$) { return 404; }
    location ~* /\.(?!well-known/) { deny all; log_not_found off; access_log off; }
    location ~* (?:\.(?:bak|conf(ig)?|dist|fla|in[ci]|log|psd|sh|sql|sw[op])|~)$ {
        deny all;
    }

    location ~* ^/admin/external {
        rewrite .* /index.php$is_args$args last;
    }

    location ~* .*/(image|video)-thumb__\d+__.* {
        try_files /var/tmp/thumbnails$uri /index.php;
        expires 2w;
        access_log off;
        add_header Cache-Control "public";
    }

    location ~* ^(?!/admin|/asset/webdav)(.+?)\.((?:css|js)(?:\.map)?|jpe?g|gif|png|svgz?|eps|exe|gz|zip|mp\d|m4a|ogg|ogv|webp|webm|pdf|docx?|xlsx?|pptx?)$ {
        try_files /var/assets$uri $uri =404;
        expires 2w;
        access_log off;
        log_not_found off;
        add_header Cache-Control "public";
    }

    rewrite ^/cache-buster-(?:\d+)/(.*) /$1 last;

    location / {
        error_page 404 /meta/404;
        try_files $static_page_root$static_page_uri.html $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        send_timeout 1800;
        fastcgi_read_timeout 1800;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        try_files $fastcgi_script_name =404;
        include fastcgi.conf;
        set $path_info $fastcgi_path_info;
        fastcgi_param PATH_INFO $path_info;
        # Tailscale Serve terminates TLS at the tailnet edge.
        fastcgi_param HTTPS on;
        fastcgi_param HTTP_X_FORWARDED_PROTO https;
        fastcgi_pass php-pimcore10;
        internal;
    }
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    ssl_certificate     /etc/letsencrypt/live/pimcore.infivea.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/pimcore.infivea.com/privkey.pem;

    root /home/ploi/pimcore.infivea.com/public;
    server_name pimcore.infivea.com;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'ECDHE-ECDSA-CHACHA20-POLY1305:ECDHE-RSA-CHACHA20-POLY1305:ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-ECDSA-AES128-SHA256:ECDHE-RSA-AES128-SHA256:ECDHE-ECDSA-AES128-SHA:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA:ECDHE-ECDSA-AES256-SHA384:ECDHE-ECDSA-AES256-SHA:ECDHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA256:DHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES256-SHA:ECDHE-ECDSA-DES-CBC3-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES128-GCM-SHA256:AES256-GCM-SHA384:AES128-SHA256:AES256-SHA256:AES128-SHA:AES256-SHA:DES-CBC3-SHA:!DSS';
    ssl_prefer_server_ciphers on;
    ssl_dhparam /etc/nginx/dhparams.pem;

    index index.php index.html;
    client_max_body_size 100m;
    large_client_header_buffers 4 32k;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    # Ploi Configuration, do not remove!
    include /etc/nginx/ploi/pimcore.infivea.com/server/*;

    access_log /var/log/nginx/pimcore.infivea.com-access.log;
    error_log  /var/log/nginx/pimcore.infivea.com-error.log error;

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    rewrite ^/cache-buster-(?:\d+)/(.*) /$1 last;

    location ~* /var/assets/.*\.php(/|$) {
        return 404;
    }
    location ~* /\.(?!well-known/) {
        deny all;
        log_not_found off;
        access_log off;
    }
    location ~* (?:\.(?:bak|conf(ig)?|dist|fla|in[ci]|log|psd|sh|sql|sw[op])|~)$ {
        deny all;
    }

    location ~* ^/admin/external {
        rewrite .* /index.php$is_args$args last;
    }

    location ~* .*/(image|video)-thumb__\d+__.* {
        try_files /var/tmp/thumbnails$uri /index.php;
        expires 2w;
        access_log off;
        add_header Cache-Control "public";
    }

    location ~* ^(?!/admin|/asset/webdav)(.+?)\.((?:css|js)(?:\.map)?|jpe?g|gif|png|svgz?|eps|exe|gz|zip|mp\d|m4a|ogg|ogv|webp|webm|pdf|docx?|xlsx?|pptx?)$ {
        try_files /var/assets$uri $uri =404;
        expires 2w;
        access_log off;
        log_not_found off;
        add_header Cache-Control "public";
    }

    # Block public admin access — admin reachable only via Tailnet (port 8081 above).
    location ^~ /admin {
        return 403;
    }

    location / {
        error_page 404 /meta/404;
        try_files $static_page_root$static_page_uri.html $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        send_timeout 1800;
        fastcgi_read_timeout 1800;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        try_files $fastcgi_script_name =404;
        include fastcgi.conf;
        set $path_info $fastcgi_path_info;
        fastcgi_param PATH_INFO $path_info;
        fastcgi_pass php-pimcore10;
        internal;
    }

    # PHP-FPM Status and Ping
    location /fpm- {
        access_log off;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        location /fpm-status {
            allow 127.0.0.1;
            deny all;
            fastcgi_pass php-pimcore10;
        }
        location /fpm-ping {
            fastcgi_pass php-pimcore10;
        }
    }

    location /nginx-status {
        allow 127.0.0.1;
        deny all;
        access_log off;
        stub_status;
    }
}

# Ploi Webserver Configuration, do not remove!
include /etc/nginx/ploi/pimcore.infivea.com/after/*;
