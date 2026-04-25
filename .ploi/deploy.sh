#!/usr/bin/env bash
# Standard deploy script for OpenDXP — runs on every Ploi deploy after Git pull.
#
# Usage in Ploi UI (Site → Deploy Script), keep ONE line:
#     cd {RELEASE} && bash .ploi/deploy.sh
#
# Ploi already does the Git fetch + checkout into {RELEASE} before calling this.
# Do NOT add `git pull` here — Ploi handles it.
#
# For a first-time cut-over from Pimcore → OpenDXP, run .ploi/first-deploy.sh ONCE
# before this script (it adds DB cleanup + var/versions migration).

set -e
set -o pipefail

echo "▶ OpenDXP deploy starting in: $(pwd)"

# 1) Wipe build caches.
rm -rf var/cache/* var/log/* 2>/dev/null || true

# 2) Composer install (production-tuned).
composer install \
    --no-interaction \
    --no-progress \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

# 3) Stop messenger workers — supervisord restarts them.
php bin/console messenger:stop-workers || true

# 4) Rebuild OpenDXP class / fieldcollection / brick definitions from var/classes/*.
php bin/console opendxp:deployment:classes-rebuild -c -d -n -f -v

# 5) Run all pending Doctrine migrations (App + OpenDxp namespaces).
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# 6) DataHub: rebuild workspaces from settings_store.
php bin/console datahub:configuration:rebuild-workspaces || true

# 7) Public assets (symlinked into public/bundles/).
php bin/console assets:install --relative --symlink public

# 8) Final prod cache build.
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

# 9) Reload PHP-FPM so OPcache picks up the new release.
echo "" | sudo -S service php8.3-fpm reload || true

echo "🚀 OpenDXP deployed successfully."
