# Staging cut-over cheat sheet

Quick, copy-paste-friendly steps for spinning up the OpenDXP staging environment from a copy of production data.

Assumptions:
- Production DB on the same host = `pimcore_royalfilter` (live, has data)
- Staging DB exists but empty = `pimcore_staging`
- Staging DB user already provisioned with `GRANT ALL ON pimcore_staging.*` = `pimcore_staging`
- Staging Ploi site already exists and points the document root to `public/`

## 1. Cloudflare

Add an A record (or CNAME to the root) for `staging.royal-filter.com` (or whatever subdomain) pointing to the staging server IP. Enable proxy if you want CF SSL.

## 2. Copy production DB into staging schema

SSH onto the server, then:

```bash
# Snapshot prod (always — even though we don't drop it)
DUMP=/tmp/pimcore_royalfilter-$(date +%Y%m%d-%H%M%S).sql

mysqldump \
    -u root -p \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF \
    pimcore_royalfilter > "$DUMP"

echo "Dump size: $(du -h "$DUMP" | cut -f1)"

# Wipe whatever is in staging schema and recreate it cleanly
mysql -u root -p <<SQL
DROP DATABASE IF EXISTS pimcore_staging;
CREATE DATABASE pimcore_staging CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
GRANT ALL PRIVILEGES ON pimcore_staging.* TO 'pimcore_staging'@'localhost';
FLUSH PRIVILEGES;
SQL

# Import prod dump into staging
mysql -u pimcore_staging -p pimcore_staging < "$DUMP"

# Sanity check — count tables
mysql -u pimcore_staging -p -e "SELECT COUNT(*) AS tables FROM information_schema.tables WHERE table_schema='pimcore_staging';"
```

## 3. Copy production assets to staging release dir

Pimcore stores binary assets under `public/var/assets/`. They live OUTSIDE the Git repo. Copy them so links in the DB resolve:

```bash
# Adjust paths to your Ploi setup
PROD_DIR=/home/ploi/royal-filter.com/current
STAGING_DIR=/home/ploi/staging.royal-filter.com/current

mkdir -p "$STAGING_DIR/public/var" "$STAGING_DIR/var"

# Assets (images, PDFs, ...) — can be a few GB; takes a while
rsync -aP --delete "$PROD_DIR/public/var/assets/"   "$STAGING_DIR/public/var/assets/"

# Optional: thumbnails (regen-able, but speeds up first page load)
rsync -aP --delete "$PROD_DIR/public/var/tmp/"      "$STAGING_DIR/public/var/tmp/"

# Versions (if you want to keep version history visible in staging)
rsync -aP "$PROD_DIR/var/versions/" "$STAGING_DIR/var/versions/"

# File ownership
sudo chown -R ploi:ploi "$STAGING_DIR/public/var" "$STAGING_DIR/var"
```

## 4. Configure Ploi staging site

### Site → Branch
`feat/opendxp-migration` (until merged to `master`)

### Site → Environment
Paste from [`.env.prod.example`](.env.prod.example) and substitute:

```dotenv
APP_ENV=prod
APP_DEBUG=false
OPENDXP_DEV_MODE=false

DB_HOST=127.0.0.1
DB_NAME=pimcore_staging
DB_USER=pimcore_staging
DB_PASSWORD=<staging-password>
DB_PORT=3306
DB_SERVER_VERSION=8.0.26

DEEPL_AUTH_KEY=<your-key>
DEEPL_ENDPOINT=https://api.deepl.com/v2/translate

# Point to the staging Vendure / Storefront if you have them, else reuse prod URLs:
VENDURE_HOST=https://vendure.royal-filter.com
PIMCORE_API_KEY=<must match Vendure side>
PIMCORE_BRIDGE_WEBHOOK_SECRET=<must match Vendure side>
STOREFRONT_URL=https://staging-storefront.royal-filter.com
CACHE_INVALIDATE_SECRET=<must match Storefront side>

MESSENGER_DSN=amqp://guest:guest@127.0.0.1:5672/%2f/messages
AMQP_HOST=127.0.0.1
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
```

### Site → Deploy Script

Single line:

```bash
cd {RELEASE} && bash .ploi/deploy.sh
```

## 5. Install PHP 8.3 if the host doesn't have it yet

```bash
sudo apt-get update
sudo apt-get install -y \
    php8.3-fpm php8.3-cli \
    php8.3-mysql php8.3-redis php8.3-amqp \
    php8.3-intl php8.3-zip php8.3-xml php8.3-gd \
    php8.3-bcmath php8.3-curl php8.3-mbstring \
    php8.3-opcache php8.3-exif php8.3-imagick \
    acl

# Make 8.3 the default `php` for the ploi user
sudo update-alternatives --set php /usr/bin/php8.3
php -v
```

In the staging Ploi site → **PHP version** → 8.3.

## 6. First deploy (one-shot Pimcore→OpenDXP migration)

After the first Ploi deploy pulls the branch:

```bash
cd /home/ploi/staging.royal-filter.com/current
set -a && source .env && set +a
bash .ploi/first-deploy.sh
```

This:
1. Backs up `pimcore_staging` to `/tmp/pimcore_staging-pre-opendxp-<timestamp>.sql`
2. Runs the SQL housekeeping (settings_store, migration_versions, cache_items)
3. `composer install --no-dev`
4. Migrates `var/versions/` (str_replace `Pimcore\` → `OpenDxp\`)
5. Hands off to `.ploi/deploy.sh` which finishes the standard deploy

## 7. Update supervisord (messenger consumer)

The transport names changed from `pimcore_*` to `opendxp_*`. Replace the existing supervisord program (typical path: `/etc/supervisor/conf.d/<site>-messenger.conf`):

```ini
[program:staging-royal-filter-messenger]
command=php /home/ploi/staging.royal-filter.com/current/bin/console messenger:consume opendxp_core opendxp_maintenance opendxp_scheduled_tasks opendxp_image_optimize opendxp_asset_update --memory-limit=250M --time-limit=3600
process_name=%(program_name)s_%(process_num)02d
numprocs=1
autostart=true
autorestart=true
user=ploi
stdout_logfile=/home/ploi/staging.royal-filter.com/current/var/log/messenger.log
stderr_logfile=/home/ploi/staging.royal-filter.com/current/var/log/messenger-error.log
```

Reload:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart staging-royal-filter-messenger:*
```

## 8. Update cron

Replace any `pimcore:maintenance` cron entry with:

```cron
*/5 * * * * cd /home/ploi/staging.royal-filter.com/current && /usr/bin/php bin/console opendxp:maintenance >/dev/null 2>&1
```

## 9. Verification

```bash
# HTTP
curl -sI https://staging.royal-filter.com/admin/login | head -1
# Expected: HTTP/2 200

# Title check
curl -sL https://staging.royal-filter.com/admin/login | grep -oE '<title>[^<]+</title>'
# Expected: <title>Welcome to OpenDXP!</title>

# DB migrations status
cd /home/ploi/staging.royal-filter.com/current
php bin/console doctrine:migrations:status | grep -E 'New|Unavailable'
# Expected: New: 0, Executed Unavailable: 0

# GraphQL endpoint
curl -X POST https://staging.royal-filter.com/opendxp-graphql-webservices/frontend \
    -H 'Content-Type: application/json' \
    -d '{"query":"{__schema{queryType{name}}}"}'
# Expected: {"data":{"__schema":{"queryType":{"name":"Query"}}}}

# Worker beats
sudo supervisorctl status staging-royal-filter-messenger:*
# Expected: RUNNING
```

Then login at `https://staging.royal-filter.com/admin` with your existing prod account — works because of `opendxp.security.password.salt: "pimcore"` in `config/config.yaml`.

## 10. Smoke-test the business-critical flows

| Flow | What to do | Expected result |
|---|---|---|
| Auto-gen Product from Whirlpool | Edit a Whirlpool, set `Generate as product = true`, save | New `Product` object appears under whirlpool |
| Auto-gen Product from RoyalFilter | Same with a RoyalFilter | Product generated |
| Variant generator | Whirlpool with multiple `royalFilterSetup` rows | Each combination becomes a Product variant |
| Classification store import | Edit any classification key | Saves without error |
| Translations (DeepL) | In a Document → translate to DE | DeepL call returns translated text |
| Vendure sync | Update a Product → POST_UPDATE event | RabbitMQ `opendxp_*` queue gets the message; bridge sends to Vendure |
| Storefront cache invalidation | Edit a NavigationItem under root | `POST {STOREFRONT_URL}/api/cache-invalidate` hits the storefront access log |
| Webhook from Vendure | `curl -X POST .../api/vendure/webhook -H 'X-Webhook-Secret: ...'` | Returns 2xx |

If anything fails, logs are at:
- `var/log/prod.log` (Symfony)
- `var/log/messenger.log` (workers)
- `var/log/<service>.log` (Ploi service logs)
