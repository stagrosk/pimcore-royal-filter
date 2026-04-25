# Staging cut-over cheat sheet

Quick, copy-paste-friendly steps for spinning up the OpenDXP staging environment from a copy of production data.

Assumptions:
- Production DB on the same host = `pimcore_royalfilter` (live, has data)
- Staging DB exists but empty = `pim_staging`
- Staging DB user already provisioned with `GRANT ALL ON pim_staging.*` = `pim_staging`
- Staging Ploi site already exists and points the document root to `public/`

## 1. Cloudflare

Add an A record (or CNAME to the root) for `pim-staging.infivea.com` (or whatever subdomain) pointing to the staging server IP. Enable proxy if you want CF SSL.

## 2. Copy production DB into staging schema

SSH onto the server, then:

```bash
# Snapshot prod (always — even though we don't drop it)
DUMP=/tmp/pimcore_royalfilter-$(date +%Y%m%d-%H%M%S).sql

mysqldump \
    -h 127.0.0.1 -u root -p \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF \
    --no-tablespaces \
    --column-statistics=0 \
    pimcore_royalfilter \
    | sed -E 's/\sDEFINER\s*=\s*`[^`]+`@`[^`]+`//g; s/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' \
    > "$DUMP"

echo "Dump size: $(du -h "$DUMP" | cut -f1)"

# Wipe whatever is in staging schema and recreate it cleanly.
mysql -h 127.0.0.1 -u root -p <<'SQL'
DROP DATABASE IF EXISTS pim_staging;
CREATE DATABASE pim_staging CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
-- 'pim_staging'@'127.0.0.1' (TCP) instead of @'localhost' (unix socket) — MariaDB / MySQL split.
GRANT ALL PRIVILEGES ON pim_staging.* TO 'pim_staging'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

# Import prod dump into staging
mysql -h 127.0.0.1 -u pim_staging -p pim_staging < "$DUMP"

# Sanity check — count tables
mysql -h 127.0.0.1 -u pim_staging -p -e "SELECT COUNT(*) AS tables FROM information_schema.tables WHERE table_schema='pim_staging';"
```

**If you already have a dump that errors with `Access denied; you need (at least one of) the SUPER or SET_USER_ID privilege(s)`**, sanitize it post-hoc and retry:

```bash
sed -i -E 's/\sDEFINER\s*=\s*`[^`]+`@`[^`]+`//g; s/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' "$DUMP"
mysql -h 127.0.0.1 -u pim_staging -p pim_staging < "$DUMP"
```

The `DEFINER=` clauses on triggers/views can only be set by a user with `SUPER` (or `SET_USER_ID` on MySQL 8.2+); stripping them lets the importing user own the objects instead.

## 3. Copy production assets to staging release dir

Pimcore stores binary assets under `public/var/assets/`. They live OUTSIDE the Git repo. Copy them so links in the DB resolve:

```bash
# Adjust paths to your Ploi setup
PROD_DIR=/home/ploi/pim.infivea.com/current
STAGING_DIR=/home/ploi/pim-staging.infivea.com/current

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
Paste contents of [`.env.staging.example`](.env.staging.example) into Ploi → Environment and replace every `__REPLACE_ME__` with the real secret.

Key staging-specific values already preset in the file:
- `DB_NAME=pim_staging`, `DB_USER=pim_staging`
- `STOREFRONT_URL=https://staging-storefront.royal-filter.com`
- Other secrets (DeepL, PIMCORE_API_KEY, PIMCORE_BRIDGE_WEBHOOK_SECRET, CACHE_INVALIDATE_SECRET) — must be set, ideally **different** from production.

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
cd /home/ploi/pim-staging.infivea.com/current
set -a && source .env && set +a
bash .ploi/first-deploy.sh
```

This:
1. Backs up `pim_staging` to `/tmp/pim_staging-pre-opendxp-<timestamp>.sql`
2. Runs the SQL housekeeping (settings_store, migration_versions, cache_items)
3. `composer install --no-dev`
4. Migrates `var/versions/` (str_replace `Pimcore\` → `OpenDxp\`)
5. Hands off to `.ploi/deploy.sh` which finishes the standard deploy

## 7. Update supervisord (messenger consumer)

The transport names changed from `pimcore_*` to `opendxp_*`. Replace the existing supervisord program (typical path: `/etc/supervisor/conf.d/<site>-messenger.conf`):

```ini
[program:pim-staging-messenger]
command=php /home/ploi/pim-staging.infivea.com/current/bin/console messenger:consume opendxp_core opendxp_maintenance opendxp_scheduled_tasks opendxp_image_optimize opendxp_asset_update --memory-limit=250M --time-limit=3600
process_name=%(program_name)s_%(process_num)02d
numprocs=1
autostart=true
autorestart=true
user=ploi
stdout_logfile=/home/ploi/pim-staging.infivea.com/current/var/log/messenger.log
stderr_logfile=/home/ploi/pim-staging.infivea.com/current/var/log/messenger-error.log
```

Reload:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart pim-staging-messenger:*
```

## 8. Update cron

Replace any `pimcore:maintenance` cron entry with:

```cron
*/5 * * * * cd /home/ploi/pim-staging.infivea.com/current && /usr/bin/php bin/console opendxp:maintenance >/dev/null 2>&1
```

## 9. Verification

```bash
# HTTP
curl -sI https://pim-staging.infivea.com/admin/login | head -1
# Expected: HTTP/2 200

# Title check
curl -sL https://pim-staging.infivea.com/admin/login | grep -oE '<title>[^<]+</title>'
# Expected: <title>Welcome to OpenDXP!</title>

# DB migrations status
cd /home/ploi/pim-staging.infivea.com/current
php bin/console doctrine:migrations:status | grep -E 'New|Unavailable'
# Expected: New: 0, Executed Unavailable: 0

# GraphQL endpoint
curl -X POST https://pim-staging.infivea.com/opendxp-graphql-webservices/frontend \
    -H 'Content-Type: application/json' \
    -d '{"query":"{__schema{queryType{name}}}"}'
# Expected: {"data":{"__schema":{"queryType":{"name":"Query"}}}}

# Worker beats
sudo supervisorctl status pim-staging-messenger:*
# Expected: RUNNING
```

Then login at `https://pim-staging.infivea.com/admin` with your existing prod account — works because of `opendxp.security.password.salt: "pimcore"` in `config/config.yaml`.

After login, go to **Settings → System Settings → General** and update **Main domain** from `pimcore.royal-filter.com` to `pim-staging.infivea.com` so generated absolute URLs (e.g. preview links) resolve to the right host.

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
