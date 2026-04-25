#!/usr/bin/env bash
# Standard deploy script for OpenDXP — runs on every Ploi deploy after Git pull.
#
# Usage in Ploi UI (Site → Deploy Script), keep ONE line:
#     cd {RELEASE} && bash .ploi/deploy.sh
#
# Ploi already does the Git fetch + checkout into {RELEASE} before calling this.
# Do NOT add `git pull` here — Ploi handles it.
#
# Manual invocation:
#     bash /home/ploi/<site>/current/.ploi/deploy.sh
#
# The script cd's to the project root (parent of .ploi/) on its own.
#
# For a first-time cut-over from Pimcore → OpenDXP, run .ploi/first-deploy.sh ONCE
# before this script (it adds DB cleanup + var/versions migration).
#
# Optional env:
#   PHP=/usr/bin/php8.4    # override the PHP binary (default: php8.3)
#   COMPOSER_BIN=/path/to/composer
#   FPM_SERVICE=php8.4-fpm

set -e
set -o pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
cd "$PROJECT_ROOT"

PHP="${PHP:-/usr/bin/php8.3}"
COMPOSER_BIN="${COMPOSER_BIN:-$(command -v composer || true)}"
FPM_SERVICE="${FPM_SERVICE:-php8.3-fpm}"

if [ ! -x "$PHP" ]; then
    echo "❌ $PHP not found. Install php8.3-fpm + php8.3-cli or set PHP=/path/to/php"
    exit 1
fi

if [ -z "$COMPOSER_BIN" ] || [ ! -f "$COMPOSER_BIN" ]; then
    echo "❌ composer not found in PATH. Set COMPOSER_BIN=/path/to/composer"
    exit 1
fi

PHP_MAJOR_MINOR=$("$PHP" -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
case "$PHP_MAJOR_MINOR" in
    8.3|8.4|8.5) ;;
    *)
        echo "❌ PHP $PHP_MAJOR_MINOR detected at $PHP — OpenDXP requires 8.3+."
        exit 1
        ;;
esac

echo "▶ OpenDXP deploy starting in: $(pwd)"
echo "▶ PHP binary: $PHP ($PHP_MAJOR_MINOR)"
echo "▶ Composer:   $COMPOSER_BIN"

# 1) Wipe build caches.
rm -rf var/cache/* var/log/* 2>/dev/null || true

# 2) Composer install (production-tuned).
"$PHP" -d memory_limit=-1 "$COMPOSER_BIN" install \
    --no-interaction \
    --no-progress \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

# 3) Stop messenger workers — supervisord restarts them.
"$PHP" bin/console messenger:stop-workers || true

# 4) Rebuild OpenDXP class / fieldcollection / brick definitions from var/classes/*.
"$PHP" bin/console opendxp:deployment:classes-rebuild -c -d -n -f -v

# 5) Run all pending Doctrine migrations (App + OpenDxp namespaces).
"$PHP" bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# 6) DataHub: rebuild workspaces from settings_store.
"$PHP" bin/console datahub:configuration:rebuild-workspaces || true

# 7) Public assets (symlinked into public/bundles/).
"$PHP" bin/console assets:install --relative --symlink public

# 8) Final prod cache build.
"$PHP" bin/console cache:clear --env=prod --no-debug
"$PHP" bin/console cache:warmup --env=prod --no-debug

# 9) Reload PHP-FPM so OPcache picks up the new release.
echo "" | sudo -S service "$FPM_SERVICE" reload || true

echo "🚀 OpenDXP deployed successfully."
