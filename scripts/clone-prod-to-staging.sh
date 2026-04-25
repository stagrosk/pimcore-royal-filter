#!/usr/bin/env bash
# Clone production DB + filesystem assets → staging.
#
# Reads credentials from each site's .env (no hardcoded secrets).
# Same MySQL host & same server assumed (per PROD.md side-by-side setup).
#
# Usage:
#     bash scripts/clone-prod-to-staging.sh                # full clone (DB + files)
#     bash scripts/clone-prod-to-staging.sh --yes          # non-interactive
#     bash scripts/clone-prod-to-staging.sh --db-only      # skip rsync
#     bash scripts/clone-prod-to-staging.sh --files-only   # skip DB
#     bash scripts/clone-prod-to-staging.sh --keep-days=7  # prune dumps older than N days (default 14, 0 = keep all)
#     PROD_ENV=/path/.env STAGING_ENV=/path/.env bash scripts/clone-prod-to-staging.sh
#
# Cron example (Sunday 03:30, log to /var/log):
#     30 3 * * 0 bash /home/ploi/pim.infivea.com/scripts/clone-prod-to-staging.sh --yes >> /var/log/clone-prod-to-staging.log 2>&1
#
# Default env locations:
#     PROD_ENV     = ~/pim.infivea.com/.env
#     STAGING_ENV  = ~/pim-staging.infivea.com/.env
#
# What it syncs (per PROD.md cut-over guide):
#     public/var/assets/   user-uploaded media (images, PDFs, …)  — required
#     public/var/tmp/      thumbnails (regen-able but slow first hit)
#     var/versions/        OpenDXP version history
#     var/email-log/       email logs (optional, small)
#
# Steps:
#   1. parse DB_* from both .env files
#   2. backup current staging DB → /tmp before drop (rollback safety)
#   3. mysqldump prod (single-transaction, no DEFINER, no GTID, no tablespaces)
#   4. drop & recreate staging schema, load prod dump
#   5. rsync prod → staging asset/thumb/versions dirs (--delete = mirror)
#   6. fixup ownership + cache reminders

set -euo pipefail

PROD_ENV="${PROD_ENV:-$HOME/pim.infivea.com/.env}"
STAGING_ENV="${STAGING_ENV:-$HOME/pim-staging.infivea.com/.env}"
DB_HOST="${DB_HOST_OVERRIDE:-127.0.0.1}"
TS="$(date +%Y%m%d-%H%M%S)"
TMP_DIR="${TMPDIR:-/tmp}"
PROD_DUMP="$TMP_DIR/clone-prod-${TS}.sql"
STAGING_BACKUP="$TMP_DIR/staging-backup-${TS}.sql"
ASSUME_YES=0
DO_DB=1
DO_FILES=1
KEEP_DAYS=14   # delete dumps older than N days from $TMP_DIR

for arg in "$@"; do
    case "$arg" in
        -y|--yes)        ASSUME_YES=1 ;;
        --db-only)       DO_FILES=0 ;;
        --files-only)    DO_DB=0 ;;
        --keep-days=*)   KEEP_DAYS="${arg#*=}" ;;
        -h|--help)       sed -n '2,35p' "$0"; exit 0 ;;
        *) echo "unknown arg: $arg"; exit 1 ;;
    esac
done

die() { echo "❌ $*" >&2; exit 1; }
hr()  { printf '%.0s─' {1..60}; echo; }

# ── parse DB_* vars from a .env file safely (no eval) ────────────────────
parse_env() {
    local file="$1"
    [ -f "$file" ] || die "env file not found: $file"
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

# Site roots derived from .env file location (sibling to project root).
PROD_ROOT="$(dirname "$(realpath "$PROD_ENV")")"
STAGING_ROOT="$(dirname "$(realpath "$STAGING_ENV")")"
[ "$PROD_ROOT" = "$STAGING_ROOT" ] && die "prod and staging share the same project root — refusing"

# Dirs to mirror (relative to project root). Skipped if source missing.
SYNC_PATHS=(
    "public/var/assets/"
    "public/var/tmp/"
    "var/versions/"
    "var/email-log/"
)

hr
echo "▶ Source (PROD):    ${PROD_DB_USER}@${PROD_DB_HOST}:${PROD_DB_PORT}/${PROD_DB_NAME}"
echo "                    $PROD_ROOT"
echo "▶ Target (STAGING): ${STAGING_DB_USER}@${STAGING_DB_HOST}:${STAGING_DB_PORT}/${STAGING_DB_NAME}"
echo "                    $STAGING_ROOT"
echo "▶ Mode:             DB=$DO_DB  Files=$DO_FILES"
[ "$DO_DB" = 1 ] && echo "▶ Dump:             $PROD_DUMP"
[ "$DO_DB" = 1 ] && echo "▶ Staging backup:   $STAGING_BACKUP"
if [ "$DO_FILES" = 1 ]; then
    echo "▶ Sync paths:"
    for p in "${SYNC_PATHS[@]}"; do
        SRC="$PROD_ROOT/$p"
        if [ -d "$SRC" ]; then
            SIZE=$(du -sh "$SRC" 2>/dev/null | cut -f1)
            echo "    $p  ($SIZE)"
        else
            echo "    $p  (skip — missing on prod)"
        fi
    done
fi
hr

if [ "$ASSUME_YES" -ne 1 ]; then
    [ "$DO_DB" = 1 ] && WARN="Staging DB will be DROPPED & RECREATED."
    [ "$DO_FILES" = 1 ] && WARN="${WARN:+$WARN }Files in staging will be MIRRORED (--delete)."
    read -r -p "Proceed? $WARN [y/N] " ans
    [[ "$ans" =~ ^[Yy]$ ]] || die "aborted"
fi

# ── DB clone ─────────────────────────────────────────────────────────────
if [ "$DO_DB" = 1 ]; then
    echo "▶ Backing up current staging DB..."
    MYSQL_PWD="$STAGING_DB_PASSWORD" mysqldump \
        -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
        --single-transaction --quick --routines --triggers \
        --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
        "$STAGING_DB_NAME" > "$STAGING_BACKUP"
    echo "  ✓ saved $(du -h "$STAGING_BACKUP" | cut -f1) → $STAGING_BACKUP"

    echo "▶ Dumping prod DB..."
    MYSQL_PWD="$PROD_DB_PASSWORD" mysqldump \
        -h "$PROD_DB_HOST" -P "$PROD_DB_PORT" -u "$PROD_DB_USER" \
        --single-transaction --quick --routines --triggers \
        --set-gtid-purged=OFF --no-tablespaces --column-statistics=0 \
        "$PROD_DB_NAME" \
        | sed -E 's/\sDEFINER\s*=\s*`[^`]+`@`[^`]+`//g; s/SQL SECURITY DEFINER/SQL SECURITY INVOKER/g' \
        > "$PROD_DUMP"
    echo "  ✓ saved $(du -h "$PROD_DUMP" | cut -f1) → $PROD_DUMP"

    echo "▶ Dropping & recreating $STAGING_DB_NAME..."
    MYSQL_PWD="$STAGING_DB_PASSWORD" mysql \
        -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
        -e "DROP DATABASE IF EXISTS \`$STAGING_DB_NAME\`; CREATE DATABASE \`$STAGING_DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

    echo "▶ Loading prod dump into staging..."
    MYSQL_PWD="$STAGING_DB_PASSWORD" mysql \
        -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
        "$STAGING_DB_NAME" < "$PROD_DUMP"

    TABLES=$(MYSQL_PWD="$STAGING_DB_PASSWORD" mysql -N \
        -h "$STAGING_DB_HOST" -P "$STAGING_DB_PORT" -u "$STAGING_DB_USER" \
        -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$STAGING_DB_NAME';")
    echo "  ✓ staging now has $TABLES tables"
fi

# ── Filesystem sync ──────────────────────────────────────────────────────
if [ "$DO_FILES" = 1 ]; then
    command -v rsync >/dev/null || die "rsync not installed"
    for p in "${SYNC_PATHS[@]}"; do
        SRC="$PROD_ROOT/$p"
        DST="$STAGING_ROOT/$p"
        if [ ! -d "$SRC" ]; then
            echo "▶ Skip $p (missing on prod)"
            continue
        fi
        echo "▶ Sync $p ..."
        mkdir -p "$DST"
        # -a: archive (perms, times, symlinks, recursive)
        # --delete: remove files on target that no longer exist on source
        # --info=progress2: single-line progress
        # --human-readable: nicer sizes
        rsync -a --delete --info=progress2 --human-readable "$SRC" "$DST" \
            || die "rsync failed for $p"
        echo "  ✓ $(du -sh "$DST" | cut -f1)"
    done
fi

# ── Cleanup old dumps (keep only KEEP_DAYS days of history) ──────────────
if [ "$KEEP_DAYS" -gt 0 ]; then
    echo "▶ Pruning dumps older than ${KEEP_DAYS} days from $TMP_DIR ..."
    DELETED=$(find "$TMP_DIR" -maxdepth 1 -type f \
        \( -name 'clone-prod-*.sql' -o -name 'staging-backup-*.sql' \) \
        -mtime "+${KEEP_DAYS}" -print -delete | wc -l)
    echo "  ✓ removed ${DELETED} file(s)"
    REMAINING=$(find "$TMP_DIR" -maxdepth 1 -type f \
        \( -name 'clone-prod-*.sql' -o -name 'staging-backup-*.sql' \) \
        | wc -l)
    REMAINING_SIZE=$(du -shc "$TMP_DIR"/clone-prod-*.sql "$TMP_DIR"/staging-backup-*.sql 2>/dev/null | tail -1 | cut -f1)
    echo "  ✓ ${REMAINING} dump(s) remain (${REMAINING_SIZE:-0})"
fi

hr
echo "🚀 Done."
[ "$DO_DB" = 1 ] && {
    echo "▶ DB rollback if needed:"
    echo "   MYSQL_PWD='\$STAGING_PWD' mysql -h $STAGING_DB_HOST -u $STAGING_DB_USER \\"
    echo "       -e \"DROP DATABASE \`$STAGING_DB_NAME\`; CREATE DATABASE \`$STAGING_DB_NAME\`;\""
    echo "   MYSQL_PWD='\$STAGING_PWD' mysql -h $STAGING_DB_HOST -u $STAGING_DB_USER \\"
    echo "       $STAGING_DB_NAME < $STAGING_BACKUP"
}
hr
echo "▶ NEXT in $STAGING_ROOT:"
echo "   rm -f var/admin/minified_javascript_core_*.js"
echo "   rm -rf var/cache/*"
echo "   php bin/console cache:clear --env=prod --no-debug"
echo "   php bin/console cache:warmup --env=prod --no-debug"
echo "   php bin/console messenger:stop-workers"
echo "   echo '' | sudo -S service php8.3-fpm reload"
