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

set -e
set -o pipefail

if [ -z "${DB_HOST:-}" ] || [ -z "${DB_USER:-}" ] || [ -z "${DB_NAME:-}" ]; then
    echo "❌ DB_HOST / DB_USER / DB_NAME must be set. Source the .env first."
    exit 1
fi

echo "▶ OpenDXP first-deploy starting in: $(pwd)"

# 1) Backup database.
BACKUP_DIR="${BACKUP_DIR:-/tmp}"
BACKUP_FILE="${BACKUP_DIR}/${DB_NAME}-pre-opendxp-$(date +%Y%m%d-%H%M%S).sql"
echo "▶ Backing up DB to ${BACKUP_FILE}"
mysqldump -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE"

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

# 3) Composer install — needed before bin/console works.
echo "▶ composer install"
composer install --no-interaction --no-progress --no-dev --prefer-dist --optimize-autoloader

# 4) Migrate serialized version files (var/versions). Idempotent — skips already-OpenDxp ones.
if [ -d var/versions ]; then
    echo "▶ Migrating var/versions/ serialized dumps"
    php bin/console app:migrate-version-files
else
    echo "▶ var/versions/ missing — skipping"
fi

# 5) Hand off to the regular deploy script for the rest (classes-rebuild, migrations, cache, FPM).
echo "▶ Handing off to .ploi/deploy.sh"
bash "$(dirname "$0")/deploy.sh"

echo "✅ First deploy complete. From now on use the regular deploy script."
echo "   DB backup is at: ${BACKUP_FILE}"
