# XMerch Docker VPS Deployment

This setup deploys XMerch as an isolated Docker stack on the same VPS that runs your Spring Boot app.

## Isolation Model

- Separate Compose project: `xmerch` (configurable via `COMPOSE_PROJECT_NAME`).
- Separate host port: `APP_HTTP_PORT` (default `8088`).
- Separate containers, networks, and named volumes.
- Separate MariaDB and Redis instances dedicated to XMerch.

## Files Added

- `/.github/workflows/deploy-vps.yml`
- `/deploy/docker/apache/Dockerfile`
- `/deploy/docker/apache/vhost.conf`
- `/deploy/docker/apache/entrypoint.sh`
- `/deploy/production/docker-compose.yml`
- `/deploy/production/.env.production.example`
- `/deploy/production/.stack.env.example`
- `/deploy/scripts/backup-local-db.ps1`
- `/deploy/scripts/restore-db-on-vps.sh`

## Required GitHub Secrets

- `VPS_HOST`: VPS IP or DNS.
- `VPS_USER`: SSH user.
- `VPS_SSH_KEY`: private key used by GitHub Actions.
- `VPS_PORT`: SSH port, usually `22`.
- `VPS_DEPLOY_PATH`: base path on VPS, for example `/opt/xmerch`.
- `XMERCH_ENV_PRODUCTION`: full Laravel production `.env` file content.
- `XMERCH_DB_DATABASE`: database name for XMerch MariaDB container.
- `XMERCH_DB_USERNAME`: database user for XMerch MariaDB container.
- `XMERCH_DB_PASSWORD`: database user password for XMerch MariaDB container.
- `XMERCH_DB_ROOT_PASSWORD`: root password for XMerch MariaDB container.
- `XMERCH_APP_HTTP_PORT`: external HTTP port exposed by XMerch container.
- `XMERCH_COMPOSE_PROJECT`: stack name, recommended `xmerch`.
- `GHCR_USERNAME`: GitHub username with package read access on VPS.
- `GHCR_TOKEN`: GitHub token/PAT with at least `read:packages` for VPS pulls.

## First Deployment

1. Add all secrets above in GitHub repository settings.
2. Ensure Docker + Docker Compose plugin are installed on the VPS.
3. Push to `main`, `master`, or `release/*`, or run workflow manually from Actions.
4. After deployment, access the app on `http://<vps-ip>:<XMERCH_APP_HTTP_PORT>`.

## Optional Worker Services

`queue` and `scheduler` are available but behind the `workers` profile.

To enable them on VPS:

```bash
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml --profile workers up -d
```

## Notes

- `XMERCH_ENV_PRODUCTION` must include `DB_HOST=db` and `REDIS_HOST=redis`.
- The deploy workflow runs:
  - `php artisan migrate --force`
  - `php artisan optimize:clear`
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`
- Uploaded files are persisted in Docker volumes (`assets/images`, `assets/temp_files`, and `project/storage`).
