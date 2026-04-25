# Production deploy — OpenDXP migration

This document covers a clean cut-over from Pimcore 11 (Community Edition, GPLv3) to OpenDXP 1.x on a Ploi-managed VPS.

## Stack changes

| | Before | After |
|---|---|---|
| Framework | Pimcore 11.5.13 | OpenDXP 1.3.x |
| PHP | 8.2 | 8.3+ |
| Symfony | 6.4 | 7.4 |
| Namespace | `Pimcore\…` | `OpenDxp\…` |
| Config root | `pimcore:` | `opendxp:` |
| Console prefix | `pimcore:*` | `opendxp:*` |

## Prerequisites on the host

```bash
# PHP 8.3 with the same extensions Pimcore 11 had
sudo apt-get install -y php8.3-fpm php8.3-cli php8.3-{mysql,redis,intl,zip,xml,gd,bcmath,curl,mbstring,opcache,exif,imagick} acl

# Disable old PHP, keep only 8.3 active
sudo update-alternatives --set php /usr/bin/php8.3
```

Composer 2.7+, MySQL 8.0+, Redis, RabbitMQ, supervisord — same as before.

## One-time pre-deploy steps (per environment)

1. **Database backup**
   ```bash
   mysqldump -u $DB_USER -p $DB_NAME > pre-opendxp-$(date +%Y%m%d-%H%M%S).sql
   ```
2. **Replace `.env`** with the contents of `.env.prod.example` and fill in real secrets.
3. **First deploy must run from the migration branch** (`feat/opendxp-migration`) until merged to `master`.
4. **First-time cut-over**: run [`.ploi/first-deploy.sh`](.ploi/first-deploy.sh) ONCE on the server. It backs up the DB, cleans `settings_store` + `migration_versions`, migrates `var/versions/` serialized dumps, then hands off to the regular `.ploi/deploy.sh`. Run it manually over SSH from the release directory:
   ```bash
   cd /home/ploi/<site>/current
   source .env
   bash .ploi/first-deploy.sh
   ```
   (The script is idempotent — safe to re-run if interrupted.)

## Ploi.io configuration

### Site → Environment

Paste the contents of [`.env.prod.example`](.env.prod.example) and replace placeholders.

### Site → Deploy Script

Both scripts live in the repo, so the Ploi field stays minimal. Use **one line**:

```bash
cd {RELEASE} && bash .ploi/deploy.sh
```

`{RELEASE}` is Ploi's placeholder for the release directory (Ploi expands it before invocation). The actual deploy steps live in [`.ploi/deploy.sh`](.ploi/deploy.sh) — no need to copy them into the Ploi UI.

Key differences vs the old Pimcore script:

| Old Pimcore script | New OpenDXP script | Why |
|---|---|---|
| `pimcore:deployment:classes-rebuild` | `opendxp:deployment:classes-rebuild` | command renamed |
| `--prefix=Pimcore\\Bundle\\CoreBundle` | (dropped — `migrate` runs all bundles) | OpenDXP migrations have a different namespace; no prefix needed |
| `datahub:graphql:rebuild-definitions` | (dropped) | command no longer exists in `open-dxp/data-hub-bundle` 1.x; `datahub:configuration:rebuild-workspaces` covers it |
| `service php8.2-fpm reload` | `service php8.3-fpm reload` | runtime version bump |
| `composer install --prefer-dist` | adds `--no-dev --classmap-authoritative` | smaller, faster prod autoloader |

### Supervisord (messenger consumer)

Update the workers config (typically `/etc/supervisor/conf.d/<site>-messenger.conf`):

```ini
[program:<site>-messenger]
command=php /home/ploi/<site>/current/bin/console messenger:consume opendxp_core opendxp_maintenance opendxp_scheduled_tasks opendxp_image_optimize opendxp_asset_update --memory-limit=250M --time-limit=3600
process_name=%(program_name)s_%(process_num)02d
numprocs=1
autostart=true
autorestart=true
user=ploi
stdout_logfile=/home/ploi/<site>/current/var/log/messenger.log
stderr_logfile=/home/ploi/<site>/current/var/log/messenger-error.log
```

**Important:** transport names changed from `pimcore_*` to `opendxp_*`. Old worker process will fail with “unknown transport” after the deploy — reload supervisord:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart <site>-messenger:*
```

### Cron (`opendxp:maintenance`)

Replace the old `pimcore:maintenance` cron entry:

```cron
*/5 * * * * cd /home/ploi/<site>/current && php bin/console opendxp:maintenance >/dev/null 2>&1
```

## First-deploy verification checklist

1. `https://<site>/admin/login` → page title `Welcome to OpenDXP!`
2. Login with existing user — works thanks to `opendxp.security.password.salt: "pimcore"` in `config/config.yaml`. If somebody resets a password later it will use the new (no-salt) hash, which is fine.
3. `https://<site>/opendxp-graphql-webservices/frontend` returns a GraphQL response
4. `php bin/console doctrine:migrations:status` shows 0 new, 0 unavailable
5. Edit a Whirlpool object → publish with `Generate as product = true` → confirm a Product is auto-generated (POST_UPDATE event)
6. Edit a NavigationItem under root → confirm storefront cache invalidation hits the storefront (check storefront access log for `POST /api/cache-invalidate`)
7. Vendure webhook: `curl -X POST https://<site>/api/vendure/webhook -H 'X-Webhook-Secret: $PIMCORE_BRIDGE_WEBHOOK_SECRET' -d '{}'` returns 2xx (or expected schema-validation error if payload is empty)

## Rollback plan

1. Restore the pre-deploy DB dump.
2. `git checkout <previous-tag>` and re-run the old Pimcore deploy script.
3. The `var/versions` migration is one-way (str_replace `Pimcore\` → `OpenDxp\`); to roll back, restore `var/versions` from the pre-deploy backup. Take a snapshot before running `app:migrate-version-files`.

## What the migration does NOT change

- Database schema (tables/columns) — OpenDXP keeps Pimcore's schema 1:1
- DB credentials / DB name (`pim-staging` stays `pim-staging`)
- ExtJS admin URL paths (`/admin/*`)
- DataHub URL prefix is `/opendxp-graphql-webservices/{client}` instead of `/pimcore-graphql-webservices/…` — **this is a public contract change** if anything outside the storefront calls the GraphQL endpoint by URL. Update consumers accordingly.
- Vendure bridge URL prefix changed: `/api/pimcore-vendure-bridge/categoryList` → `/api/opendxp-vendure-bridge/categoryList`. Update Vendure consumers if they use this endpoint.
