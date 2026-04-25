#!/usr/bin/env bash
# Ploi.io deploy script for OpenDXP (post-Pimcore migration).
# Place this content in Ploi → Site → Deploy Script (or run directly: bash .ploi/deploy.sh)

set -e
set -o pipefail

cd "{RELEASE}" 2>/dev/null || cd "$(dirname "$0")/.."

echo "▶ Deploy starting in: $(pwd)"

# 1) Pull latest code (Ploi already does this; here for safety on manual runs).
git reset --hard origin/master
git pull origin master --rebase=false

# 2) Wipe build caches.
rm -rf var/cache/* var/log/* 2>/dev/null || true

# 3) Composer install (production-tuned).
composer install \
    --no-interaction \
    --no-progress \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative

# 4) Stop messenger workers (supervisord will restart them).
php bin/console messenger:stop-workers || true

# 5) Rebuild OpenDXP class-, fieldcollection- and brick-definitions from var/classes/*.
php bin/console opendxp:deployment:classes-rebuild -c -d -n -f -v

# 6) Doctrine migrations — run all available (OpenDxp + App namespaces).
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# 7) DataHub: rebuild workspaces from settings_store + GraphQL definitions.
php bin/console datahub:configuration:rebuild-workspaces || true

# 8) Public assets (symlinked).
php bin/console assets:install --relative --symlink public

# 9) Final cache warm-up in prod env.
php bin/console cache:clear --env=prod --no-debug
php bin/console cache:warmup --env=prod --no-debug

# 10) Reload PHP-FPM so OPcache picks up the new release.
echo "" | sudo -S service php8.3-fpm reload || true

echo "🚀 OpenDXP deployed successfully."
