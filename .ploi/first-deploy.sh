#!/usr/bin/env bash
# One-shot cut-over script: Pimcore → OpenDXP on a host that previously ran Pimcore.
#
# Run ONCE on the production server before the first regular .ploi/deploy.sh:
#
#     cd {RELEASE} && bash .ploi/first-deploy.sh
#
# Idempotent: safe to re-run if it fails midway. Skips work that's already done.
#
# Required env (must be loaded in shell, e.g. `source .env`):
#   DB_HOST, DB_PORT, DB_USER, DB_PASSWORD, DB_NAME
#
# Optional env:
#   PHP=/usr/bin/php8.4   # override the PHP binary (default: php8.3)

set -e
set -o pipefail

PHP="${PHP:-/usr/bin/php8.3}"
COMPOSER="${COMPOSER:-/usr/bin/composer}"

if [ ! -x "$PHP" ]; then
    echo "❌ $PHP not found. Install php8.3-fpm + php8.3-cli or set PHP=/path/to/php"
    exit 1
fi

if [ -z "${DB_HOST:-}" ] || [ -z "${DB_USER:-}" ] || [ -z "${DB_NAME:-}" ]; then
    echo "❌ DB_HOST / DB_USER / DB_NAME must be set. Source the .env first."
    exit 1
fi

echo "▶ OpenDXP first-deploy starting in: $(pwd)"
echo "▶ PHP binary: $PHP ($($PHP -r 'echo PHP_VERSION;'))"

# 1) Backup database.
#    --no-tablespaces avoids 'PROCESS privilege' error on least-privilege users.
#    --column-statistics=0 avoids 'unknown table column_statistics' on MySQL < 8.0.18.
BACKUP_DIR="${BACKUP_DIR:-/tmp}"
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}-pre-opendxp-$(date +%Y%m%d-%H%M%S).sql"
echo "▶ Backing up DB to ${BACKUP_FILE}"
mysqldump \
    -h "$DB_HOST" -P "${DB_PORT:-3306}" \
    -u "$DB_USER" -p"$DB_PASSWORD" \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF \
    --no-tablespaces \
    --column-statistics=0 \
    "$DB_NAME" > "$BACKUP_FILE"
echo "  Dump size: $(du -h "$BACKUP_FILE" | cut -f1)"

# 2) DB cleanup — Pimcore → OpenDXP namespace housekeeping.
#    The App\Migrations\Version20260424OpenDxpRename does this too, but doing it
#    pre-deploy avoids the migrator's "previously executed unavailable" warning.
echo "▶ Cleaning settings_store + migration_versions"
mysql -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<'SQL'
UPDATE settings_store
   SET id = REPLACE(id, 'BUNDLE_INSTALLED__Pimcore', 'BUNDLE_INSTALLED__OpenDxp')
 WHERE id LIKE 'BUNDLE_INSTALLED__Pimcore%';

DELETE FROM settings_store
 WHERE id LIKE 'BUNDLE_INSTALLED__OpenDxp%Pimcore%Bundle';

DELETE FROM migration_versions
 WHERE version REGEXP '^Pimcore\\\\Bundle\\\\(Core|DataHub|Uuid)Bundle\\\\';

TRUNCATE TABLE cache_items;
SQL

# 3) Composer install — runs through PHP 8.3 explicitly (the system default may be 8.2).
echo "▶ composer install"
"$PHP" -d memory_limit=-1 "$COMPOSER" install \
    --no-interaction --no-progress --no-dev --prefer-dist --optimize-autoloader

# 4) Migrate serialized version files (var/versions). Idempotent — skips already-OpenDxp ones.
if [ -d var/versions ]; then
    echo "▶ Migrating var/versions/ serialized dumps"
    "$PHP" bin/console app:migrate-version-files
else
    echo "▶ var/versions/ missing — skipping"
fi

# 5) Hand off to the regular deploy script for the rest (classes-rebuild, migrations, cache, FPM).
echo "▶ Handing off to .ploi/deploy.sh"
PHP="$PHP" COMPOSER="$COMPOSER" bash "$(dirname "$0")/deploy.sh"

echo "✅ First deploy complete. From now on use the regular deploy script."
echo "   DB backup is at: ${BACKUP_FILE}"
