#!/usr/bin/env bash
set -euo pipefail

BACKUP_FILE="${1:-}"
STACK_DIR="${2:-/opt/xmerch/production}"

if [[ -z "${BACKUP_FILE}" ]]; then
  echo "Usage: $0 <backup.sql|backup.sql.gz|backup.zip> [stack_dir]"
  exit 1
fi

if [[ ! -f "${BACKUP_FILE}" ]]; then
  echo "Backup file not found: ${BACKUP_FILE}"
  exit 1
fi

if [[ ! -d "${STACK_DIR}" ]]; then
  echo "Stack directory not found: ${STACK_DIR}"
  exit 1
fi

cd "${STACK_DIR}"

COMPOSE_PROJECT_NAME="xmerch"
DB_DATABASE="xmerch"
DB_USERNAME="xmerch"
DB_PASSWORD=""

if [[ -f ".stack.env" ]]; then
  # shellcheck disable=SC1091
  source .stack.env
fi

COMPOSE_PROJECT_NAME="${COMPOSE_PROJECT_NAME:-xmerch}"
DB_DATABASE="${DB_DATABASE:-xmerch}"
DB_USERNAME="${DB_USERNAME:-xmerch}"

if [[ -z "${DB_PASSWORD:-}" ]]; then
  echo "DB_PASSWORD is empty in .stack.env"
  exit 1
fi

docker compose --project-name "${COMPOSE_PROJECT_NAME}" --env-file .stack.env -f docker-compose.yml up -d db

import_plain_sql() {
  local sql_path="$1"
  docker compose --project-name "${COMPOSE_PROJECT_NAME}" --env-file .stack.env -f docker-compose.yml \
    exec -T -e MYSQL_PWD="${DB_PASSWORD}" db \
    mariadb -u"${DB_USERNAME}" "${DB_DATABASE}" < "${sql_path}"
}

ext="${BACKUP_FILE##*.}"
case "${ext}" in
  sql)
    import_plain_sql "${BACKUP_FILE}"
    ;;
  gz)
    gunzip -c "${BACKUP_FILE}" | docker compose --project-name "${COMPOSE_PROJECT_NAME}" --env-file .stack.env -f docker-compose.yml \
      exec -T -e MYSQL_PWD="${DB_PASSWORD}" db mariadb -u"${DB_USERNAME}" "${DB_DATABASE}"
    ;;
  zip)
    if ! command -v unzip >/dev/null 2>&1; then
      echo "unzip is required to restore from .zip backup."
      exit 1
    fi
    unzip -p "${BACKUP_FILE}" | docker compose --project-name "${COMPOSE_PROJECT_NAME}" --env-file .stack.env -f docker-compose.yml \
      exec -T -e MYSQL_PWD="${DB_PASSWORD}" db mariadb -u"${DB_USERNAME}" "${DB_DATABASE}"
    ;;
  *)
    echo "Unsupported backup extension: ${ext}"
    echo "Use .sql, .sql.gz or .zip"
    exit 1
    ;;
esac

echo "Database restore completed into ${DB_DATABASE} (${COMPOSE_PROJECT_NAME})."

