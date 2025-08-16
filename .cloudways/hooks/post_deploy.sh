#!/usr/bin/env bash
set -e

# Cloudways Post Deploy Hook
# This script runs automatically after each Git deployment on Cloudways
# It assumes current working directory is the application root of the new release

PHP="php"
COMPOSER="composer"

echo "[post_deploy] Starting post-deploy tasks..."

# 1) Install PHP dependencies (production)
echo "[post_deploy] Running composer install..."
$COMPOSER install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# 2) Laravel maintenance tasks
echo "[post_deploy] Clearing and optimizing caches..."
$PHP artisan config:clear || true
$PHP artisan route:clear || true
$PHP artisan view:clear || true
$PHP artisan optimize || true

# 3) Storage symlink (idempotent)
if [ ! -L "public/storage" ]; then
  echo "[post_deploy] Creating storage symlink..."
  $PHP artisan storage:link || true
else
  echo "[post_deploy] Storage symlink exists."
fi

# 4) Migrations (optional, disabled by default for safety)
# Uncomment the next line if you want to auto-run DB migrations on deploy
# $PHP artisan migrate --force

echo "[post_deploy] Completed post-deploy tasks."

