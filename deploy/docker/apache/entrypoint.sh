#!/usr/bin/env sh
set -eu

APP_ROOT="/var/www/html"
LARAVEL_ROOT="${APP_ROOT}/project"

mkdir -p \
  "${LARAVEL_ROOT}/storage/framework/cache" \
  "${LARAVEL_ROOT}/storage/framework/sessions" \
  "${LARAVEL_ROOT}/storage/framework/views" \
  "${LARAVEL_ROOT}/storage/logs" \
  "${LARAVEL_ROOT}/bootstrap/cache" \
  "${APP_ROOT}/assets/images" \
  "${APP_ROOT}/assets/temp_files"

chown -R www-data:www-data \
  "${LARAVEL_ROOT}/storage" \
  "${LARAVEL_ROOT}/bootstrap/cache" \
  "${APP_ROOT}/assets/images" \
  "${APP_ROOT}/assets/temp_files" || true

# Remove host-generated runtime cache that can lock wrong env/config values.
rm -f "${LARAVEL_ROOT}/bootstrap/cache/config.php" "${LARAVEL_ROOT}/bootstrap/cache/routes-v7.php"

exec "$@"

