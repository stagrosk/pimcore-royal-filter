# Production cut-over cheat sheet

Side-by-side migration from legacy Pimcore 11 to OpenDXP. New site lives at
`pim.infivea.com`; legacy site stays at `pimcore.infivea.com` until the swap.

## Why side-by-side

- Zero downtime: customers keep using `pimcore.infivea.com` until DNS swap
- Atomic rollback: if anything's wrong, just don't change DNS
- Same MySQL host → fast DB clone
- Two independent PHP-FPM pools (8.2 for legacy, 8.3 for OpenDXP) — no conflict

## High-level timeline

```
day 0: clone prod data → new site, deploy OpenDXP, smoke test on .infivea.com IP directly
day 1: hand over to QA / users for UAT on temporary URL (Hosts file, or pim-uat.infivea.com)
day 2: DNS swap pim.infivea.com → server, CF orange cloud
day 3+: monitor, gather feedback
day 7: deactivate pimcore.infivea.com, drop old DB after backup retention period
```

## 1. Cloudflare DNS

- **Pre-swap**: Add A record `pim.infivea.com` → server IP, **grey cloud (DNS only)** so LE HTTP-01 challenge works.
- **Post-swap**: Switch to **orange cloud (Proxied)** for CF SSL/CDN/WAF on the new site.

Until the swap is done, point your local `/etc/hosts` for testing:
```
142.132.226.101  pim.infivea.com
```

## 2. Database — clone production into new schema

```bash
DUMP=/tmp/pimcore_royalfilter-$(date +%Y%m%d-%H%M%S).sql

# Snapshot prod with the same flags first-deploy.sh uses
mysqldump \
    -h 127.0.0.1 -u root -p \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
    pimcore_royalfilter \
    | sed -E 's/\sDEFINER\s*=\s*`[^`]+`@`[^`]+`//g; s/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' \
    > "$DUMP"

# Create the new prod DB + user
mysql -h 127.0.0.1 -u root -p <<'SQL'
DROP DATABASE IF EXISTS pim_prod;
CREATE DATABASE pim_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER IF NOT EXISTS 'pim_prod'@'127.0.0.1' IDENTIFIED BY 'GENERATE_STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON pim_prod.* TO 'pim_prod'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

# Import
mysql -h 127.0.0.1 -u pim_prod -p pim_prod < "$DUMP"

# Sanity
mysql -h 127.0.0.1 -u pim_prod -p -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='pim_prod';"
```

**The legacy `pimcore_royalfilter` DB stays untouched** — that's the rollback plan.

## 3. Copy production assets to new site dir

```bash
LEGACY_DIR=/home/ploi/pimcore.infivea.com/current
NEW_DIR=/home/ploi/pim.infivea.com/current

mkdir -p "$NEW_DIR/public/var" "$NEW_DIR/var"

# Assets (images, PDFs, …)
rsync -aP "$LEGACY_DIR/public/var/assets/"   "$NEW_DIR/public/var/assets/"
# Thumbnails (regen-able, but speeds up first page load)
rsync -aP "$LEGACY_DIR/public/var/tmp/"      "$NEW_DIR/public/var/tmp/"
# Versions (so version history shows up in OpenDXP admin)
rsync -aP "$LEGACY_DIR/var/versions/"        "$NEW_DIR/var/versions/"

sudo chown -R ploi:ploi "$NEW_DIR/public/var" "$NEW_DIR/var"
```

## 4. Configure Ploi prod site

### Site → Branch
`master`

### Site → Environment
Paste contents of [`.env.prod.example`](.env.prod.example) and substitute:

```dotenv
APP_ENV=prod
APP_DEBUG=false
OPENDXP_DEV_MODE=false

DB_HOST=127.0.0.1
DB_NAME=pim_prod
DB_USER=pim_prod
DB_PASSWORD=__from_password_manager__
DB_PORT=3306
DB_SERVER_VERSION=8.0.26

DEEPL_AUTH_KEY=__prod_deepl_key__
DEEPL_ENDPOINT=https://api.deepl.com/v2/translate

VENDURE_HOST=https://vendure.infivea.com
PIMCORE_API_KEY=__same_as_vendure_side__
PIMCORE_BRIDGE_WEBHOOK_SECRET=__same_as_vendure_side__
STOREFRONT_URL=https://storefront.infivea.com
CACHE_INVALIDATE_SECRET=__same_as_storefront_side__

MESSENGER_DSN=amqp://guest:guest@127.0.0.1:5672/%2f/messages
AMQP_HOST=127.0.0.1
AMQP_PORT=5672
AMQP_USER=guest
AMQP_PASSWORD=guest
```

### Site → Deploy Script
```bash
cd {RELEASE} && bash .ploi/deploy.sh
```

### Site → PHP version
8.3 (separate pool from the legacy 8.2 — both can coexist).

## 5. Nginx vhost

Two phases (same as staging):

```bash
cd /home/ploi/pim.infivea.com/current
git pull origin master

# Phase 1 — HTTP only (before LE)
sudo cp .ploi/nginx/prod/pim.infivea.com.http \
        /etc/nginx/sites-available/pim.infivea.com
sudo ln -s /etc/nginx/sites-available/pim.infivea.com /etc/nginx/sites-enabled/pim.infivea.com  # if not already
sudo nginx -t && sudo systemctl reload nginx

# Test (Hosts file or curl --resolve)
curl --resolve pim.infivea.com:80:142.132.226.101 -I http://pim.infivea.com/
```

Issue LE cert (CF must be grey cloud):
```bash
sudo certbot certonly --webroot -w /home/ploi/pim.infivea.com/public -d pim.infivea.com --non-interactive --agree-tos -m tvoj@email
```

```bash
# Phase 2 — SSL on
sudo cp .ploi/nginx/prod/pim.infivea.com.ssl \
        /etc/nginx/sites-available/pim.infivea.com
sudo nginx -t && sudo systemctl reload nginx
```

## 6. First deploy

```bash
cd /home/ploi/pim.infivea.com/current
set -a && source .env && set +a
unset COMPOSER  # avoid clash with composer's COMPOSER env var
bash .ploi/first-deploy.sh
```

This:
1. Backs up `pim_prod` to `/tmp/pim_prod-pre-opendxp-<timestamp>.sql`
2. Runs the SQL housekeeping (`settings_store`, `migration_versions`, `cache_items`)
3. `composer install --no-dev`
4. Migrates `var/versions/` (str_replace `Pimcore\` → `OpenDxp\`)
5. Hands off to `.ploi/deploy.sh` which finishes the standard deploy

## 7. Supervisord (separate worker for the new site)

```bash
sudo tee /etc/supervisor/conf.d/pim-prod-messenger.conf <<'EOF'
[program:pim-prod-messenger]
command=/usr/bin/php8.3 /home/ploi/pim.infivea.com/current/bin/console messenger:consume opendxp_core opendxp_maintenance opendxp_scheduled_tasks opendxp_image_optimize opendxp_asset_update --memory-limit=250M --time-limit=3600
process_name=%(program_name)s_%(process_num)02d
numprocs=2
autostart=true
autorestart=true
user=ploi
stdout_logfile=/home/ploi/pim.infivea.com/current/var/log/messenger.log
stderr_logfile=/home/ploi/pim.infivea.com/current/var/log/messenger-error.log
EOF

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl status pim-prod-messenger:*
```

## 8. Cron

```bash
(sudo -u ploi crontab -l 2>/dev/null; echo '*/5 * * * * cd /home/ploi/pim.infivea.com/current && /usr/bin/php8.3 bin/console opendxp:maintenance >/dev/null 2>&1') | sudo -u ploi crontab -
```

## 9. UAT — test on the new site BEFORE DNS swap

Until DNS is swapped, use `--resolve` or `/etc/hosts`:

```bash
# HTTP smoke
curl --resolve pim.infivea.com:443:142.132.226.101 -I https://pim.infivea.com/admin/login
curl --resolve pim.infivea.com:443:142.132.226.101 -sL https://pim.infivea.com/admin/login | grep -oE '<title>[^<]+</title>'

# Migrations
cd /home/ploi/pim.infivea.com/current
/usr/bin/php8.3 bin/console doctrine:migrations:status | grep -E 'New|Unavailable|Latest'

# GraphQL
curl --resolve pim.infivea.com:443:142.132.226.101 \
    -X POST https://pim.infivea.com/opendxp-graphql-webservices/frontend \
    -H 'Content-Type: application/json' \
    -d '{"query":"{__schema{queryType{name}}}"}'

# Workers
sudo supervisorctl status pim-prod-messenger:*
```

Browser test via `/etc/hosts`:
```
142.132.226.101  pim.infivea.com
```
Then `https://pim.infivea.com/admin` → login.

## 10. Smoke-test biznis flows on UAT

| Flow | Verification |
|---|---|
| Auto-gen Product (Whirlpool) | Edit Whirlpool, set `Generate as product=true`, save → Product appears |
| Auto-gen Product (RoyalFilter) | Same |
| Variant generator | Whirlpool with multiple `royalFilterSetup` → variants generated |
| Classification store | Edit a classification key → saves |
| DeepL translate | Document → translate to DE |
| Vendure sync | POST_UPDATE on Product → message in opendxp_* RabbitMQ queue → bridge sends to Vendure |
| Storefront cache invalidation | Edit NavigationItem under root → POST hits storefront `/api/cache-invalidate` |
| Webhook from Vendure | `curl -X POST https://pim.infivea.com/api/vendure/webhook -H 'X-Webhook-Secret: $PIMCORE_BRIDGE_WEBHOOK_SECRET'` returns 2xx |
| Login (existing user) | Old prod password works (config `opendxp.security.password.salt: pimcore`) |

## 11. DNS swap (cut-over moment)

Once UAT is green:

1. **Cloudflare → DNS → A record `pim.infivea.com`** stays grey cloud at first
2. Update **Vendure** + **Storefront** environment to point at `https://pim.infivea.com` (replace any `https://pimcore.infivea.com` references they had)
3. Restart Vendure / Storefront services
4. Switch CF to **orange cloud (Proxied)** for `pim.infivea.com`
5. Add a 301 redirect from `pimcore.infivea.com/*` → `https://pim.infivea.com/*` for any external links / bookmarks (optional, but kind to users)

## 12. Decommission legacy site

After 7-14 days of stable prod operation:

```bash
# Final backup of the old DB
mysqldump -h 127.0.0.1 -u root -p \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
    pimcore_royalfilter > /backup/pimcore_royalfilter-final-$(date +%Y%m%d).sql

# Drop the legacy site in Ploi UI: Sites → pimcore.infivea.com → Delete site
# This removes /home/ploi/pimcore.infivea.com/, the nginx vhost, the SSL cert, etc.

# Drop the legacy DB
mysql -h 127.0.0.1 -u root -p -e "DROP DATABASE pimcore_royalfilter; DROP USER 'pimcore_royalfilter'@'localhost';"

# Remove the legacy supervisord worker
sudo rm /etc/supervisor/conf.d/pimcore-royalfilter-messenger.conf
sudo supervisorctl reread && sudo supervisorctl update

# Optional: uninstall PHP 8.2 once nothing else uses it
sudo apt-get remove --purge php8.2-fpm php8.2-cli 'php8.2-*'
sudo apt-get autoremove
```

## Rollback plan (any time before step 11)

1. CF DNS for `pim.infivea.com` → don't promote / leave grey
2. The legacy `pimcore.infivea.com` keeps serving traffic from `pimcore_royalfilter` DB — it was never touched
3. New site can be wiped in Ploi without consequence: `pim.infivea.com` site delete + `DROP DATABASE pim_prod;`

After step 11 (DNS swapped):

1. Switch CF DNS for `pim.infivea.com` back to grey cloud or remove
2. Restore Vendure / Storefront env vars to point at `https://pimcore.infivea.com`
3. Re-enable legacy site if it was deactivated

The cut-over only becomes hard to roll back **after step 12** when the legacy DB is dropped. That's why we wait 7-14 days first.
