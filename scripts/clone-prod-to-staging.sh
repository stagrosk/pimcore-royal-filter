#!/usr/bin/env bash
# Clone production DB → staging DB.
#
# Reads credentials from each site's .env (no hardcoded secrets).
# Same MySQL host assumed (per PROD.md side-by-side setup).
#
# Usage:
#     bash scripts/clone-prod-to-staging.sh                # interactive (asks confirmation)
#     bash scripts/clone-prod-to-staging.sh --yes          # non-interactive
#     PROD_ENV=/path/.env STAGING_ENV=/path/.env bash scripts/clone-prod-to-staging.sh
#
# Default env locations:
#     PROD_ENV     = ~/pim.infivea.com/.env
#     STAGING_ENV  = ~/pim-staging.infivea.com/.env
#
# Steps:
#   1. parse DB_* from both .env files
#   2. mysqldump prod (single-transaction, no DEFINER, no GTID, no tablespaces)
#   3. backup current staging DB to /tmp before load (safety net)
#   4. drop & recreate staging schema, load prod dump
#   5. clean up dump files (kept in /tmp for 24h via filename mtime)

set -euo pipefail

PROD_ENV="${PROD_ENV:-$HOME/pim.infivea.com/.env}"
STAGING_ENV="${STAGING_ENV:-$HOME/pim-staging.infivea.com/.env}"
DB_HOST="${DB_HOST_OVERRIDE:-127.0.0.1}"
TS="$(date +%Y%m%d-%H%M%S)"
TMP_DIR="${TMPDIR:-/tmp}"
PROD_DUMP="$TMP_DIR/clone-prod-${TS}.sql"
STAGING_BACKUP="$TMP_DIR/staging-backup-${TS}.sql"
ASSUME_YES=0

for arg in "$@"; do
    case "$arg" in
        -y|--yes) ASSUME_YES=1 ;;
        -h|--help) sed -n '2,30p' "$0"; exit 0 ;;
        *) echo "unknown arg: $arg"; exit 1 ;;
    esac
done

die() { echo "❌ $*" >&2; exit 1; }
hr()  { printf '%.0s─' {1..60}; echo; }

# ── 1. Parse DB_* vars from a .env file safely (no eval) ──────────────────
parse_env() {
    local file="$1"
    [ -f "$file" ] || die "env file not found: $file"
    # Match DB_NAME, DB_USER, DB_PASSWORD, DB_HOST, DB_PORT — ignore quotes.
    awk -F= '
        /^DB_(HOST|PORT|NAME|USER|PASSWORD)=/ {
            val=$2
            sub(/^[ \t]*/, "", val); sub(/[ \t]*$/, "", val)
            sub(/^["'\'']/, "", val); sub(/["'\'']$/, "", val)
            print $1"="val
        }' "$file"
}

eval_env() {
    local prefix="$1" file="$2" line key val
    while IFS= read -r line; do
        key="${line%%=*}"; val="${line#*=}"
        printf -v "${prefix}_${key}" '%s' "$val"
    done < <(parse_env "$file")
}

eval_env PROD    "$PROD_ENV"
eval_env STAGING "$STAGING_ENV"

: "${PROD_DB_NAME:?missing DB_NAME in $PROD_ENV}"
: "${PROD_DB_USER:?missing DB_USER in $PROD_ENV}"
: "${PROD_DB_PASSWORD:?missing DB_PASSWORD in $PROD_ENV}"
PROD_DB_HOST="${PROD_DB_HOST:-$DB_HOST}"
PROD_DB_PORT="${PROD_DB_PORT:-3306}"

: "${STAGING_DB_NAME:?missing DB_NAME in $STAGING_ENV}"
: "${STAGING_DB_USER:?missing DB_USER in $STAGING_ENV}"
: "${STAGING_DB_PASSWORD:?missing DB_PASSWORD in $STAGING_ENV}"
STAGING_DB_HOST="${STAGING_DB_HOST:-$DB_HOST}"
STAGING_DB_PORT="${STAGING_DB_PORT:-3306}"

[ "$PROD_DB_NAME" = "$STAGING_DB_NAME" ] && die "prod and staging DB names are identical — refusing"

hr
echo "▶ Source (PROD):    ${PROD_DB_USER}@${PROD_DB_HOST}:${PROD_DB_PORT}/${PROD_DB_NAME}"
echo "▶ Target (STAGING): ${STAGING_DB_USER}@${STAGING_DB_HOST}:${STAGING_DB_PORT}/${STAGING_DB_NAME}"
echo "▶ Dump:             $PROD_DUMP"
echo "▶ Staging backup:   $STAGING_BACKUP"
hr

if [ "$ASSUME_YES" -ne 1 ]; then
    read -r -p "Proceed? Staging DB will be DROPPED & RECREATED. [y/N] " ans
    [[ "$ans" =~ ^[Yy]$ ]] || die "aborted"
fi

# ── 2. Backup current staging (rollback safety) ──────────────────────────
echo "▶ Backing up current staging DB..."
MYSQL_PWD="$STAGING_DB_PASSWORD" mysqldump \
    -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
    "$STAGING_DB_NAME" > "$STAGING_BACKUP"
echo "  ✓ saved $(du -h "$STAGING_BACKUP" | cut -f1) → $STAGING_BACKUP"

# ── 3. Dump production ───────────────────────────────────────────────────
echo "▶ Dumping prod DB..."
MYSQL_PWD="$PROD_DB_PASSWORD" mysqldump \
    -h "$PROD_DB_HOST" -P "$PROD_DB_PORT" -u "$PROD_DB_USER" \
    --single-transaction --quick --routines --triggers \
    --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
    "$PROD_DB_NAME" \
    | sed -E 's/\sDEFINER\s*=\s*`[^`]+`@`[^`]+`//g; s/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' \
    > "$PROD_DUMP"
echo "  ✓ saved $(du -h "$PROD_DUMP" | cut -f1) → $PROD_DUMP"

# ── 4. Drop & recreate staging schema, load dump ─────────────────────────
echo "▶ Dropping & recreating $STAGING_DB_NAME..."
MYSQL_PWD="$STAGING_DB_PASSWORD" mysql \
    -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
    -e "DROP DATABASE IF EXISTS \`$STAGING_DB_NAME\`; CREATE DATABASE \`$STAGING_DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

echo "▶ Loading prod dump into staging..."
MYSQL_PWD="$STAGING_DB_PASSWORD" mysql \
    -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
    "$STAGING_DB_NAME" < "$PROD_DUMP"

# ── 5. Sanity check ──────────────────────────────────────────────────────
TABLES=$(MYSQL_PWD="$STAGING_DB_PASSWORD" mysql -N \
    -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$STAGING_DB_NAME';")
echo "  ✓ staging now has $TABLES tables"

hr
echo "🚀 Done. Rollback if needed:"
echo "   MYSQL_PWD='\$STAGING_PWD' mysql -h $STAGING_DB_HOST -u $STAGING_DB_USER \\"
echo "       -e \"DROP DATABASE \`$STAGING_DB_NAME\`; CREATE DATABASE \`$STAGING_DB_NAME\`;\""
echo "   MYSQL_PWD='\$STAGING_PWD' mysql -h $STAGING_DB_HOST -u $STAGING_DB_USER \\"
echo "       $STAGING_DB_NAME < $STAGING_BACKUP"
hr
echo "▶ NEXT: clear staging app cache + reindex backend search:"
echo "   cd ~/pim-staging.infivea.com"
echo "   rm -f var/admin/minified_javascript_core_*.js"
echo "   php bin/console cache:clear --env=prod --no-debug"
echo "   php bin/console messenger:stop-workers"
