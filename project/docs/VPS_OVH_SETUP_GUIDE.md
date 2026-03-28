# Full Guide: VPS + OVH + Domain Setup For XMerch

This guide covers both:
- VPS preparation (Docker + Nginx + security).
- Domain setup (`xmerch.run.place`) and TLS.
- Database backup from current environment and restore into VPS.

It is aligned with:
- `C:/laragon/www/xmerch/.github/workflows/deploy-vps.yml`
- `C:/laragon/www/xmerch/deploy/production/docker-compose.yml`
- `C:/laragon/www/xmerch/deploy/scripts/backup-local-db.ps1`
- `C:/laragon/www/xmerch/deploy/scripts/restore-db-on-vps.sh`

## 0. Decide Production Values First

Before setup, lock these values:

- `PROD_DOMAIN`: production domain (example: `shop.yourdomain.com`).
- `PROD_DEPLOY_PATH`: VPS folder (example: `/srv/xmerch-prod` or `/opt/xmerch-prod`).
- `PROD_COMPOSE_PROJECT`: isolated stack name (example: `xmerch_prod`).
- `PROD_HTTP_BIND`: localhost-bound app port (example: `127.0.0.1:8092`).

Use those values consistently in:
- GitHub secrets (`VPS_DEPLOY_PATH`, `XMERCH_COMPOSE_PROJECT`, `XMERCH_APP_HTTP_PORT`).
- `XMERCH_ENV_PRODUCTION` (`APP_URL=https://<PROD_DOMAIN>`).
- Nginx `server_name` and proxy target.

## 1. Target Architecture

- Spring Boot app stays in its own containers/ports.
- XMerch runs as a separate Compose project: `xmerch`.
- XMerch internal services:
  - `web` (Laravel + Apache)
  - `db` (MariaDB)
  - `redis`
  - optional `queue` and `scheduler`
- Public traffic:
  - `xmerch.run.place` -> Nginx (80/443) -> `127.0.0.1:8088` -> XMerch `web`

## 2. OVH VPS Setup

## 2.1 Create/Prepare VPS in OVH

1. Create VPS with Ubuntu 22.04 LTS (recommended).
2. Ensure a public IPv4 is attached.
3. In OVH firewall/security group, allow inbound:
   - `22` (SSH)
   - `80` (HTTP)
   - `443` (HTTPS)
4. Keep all DB/Redis ports closed publicly (`3306`, `6379` should stay private).

## 2.2 Initial Server Hardening

SSH into VPS as root (or initial sudo user), then:

```bash
apt update && apt upgrade -y
apt install -y ca-certificates curl git ufw fail2ban

# create deploy user if not created yet
adduser deploy
usermod -aG sudo deploy
```

Optional but recommended:
- Disable root SSH login.
- Disable password SSH login and use keys only.

Example:

```bash
sed -i 's/^#\?PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
sed -i 's/^#\?PasswordAuthentication.*/PasswordAuthentication no/' /etc/ssh/sshd_config
systemctl restart ssh
```

## 2.3 Firewall

```bash
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable
ufw status
```

## 3. Install Docker + Compose Plugin

Run as `deploy` user (or sudo):

```bash
apt update
apt install -y docker.io docker-compose-plugin nginx
systemctl enable --now docker
usermod -aG docker deploy
```

Re-login to apply group membership, then verify:

```bash
docker --version
docker compose version
```

## 4. Prepare XMerch Deployment Directory

```bash
mkdir -p /opt/xmerch/production
chown -R deploy:deploy /opt/xmerch
```

This must match GitHub secret:
- `VPS_DEPLOY_PATH=/opt/xmerch`

If you want a different folder, use it directly, for example:

```bash
mkdir -p /srv/xmerch-prod/production
chown -R deploy:deploy /srv/xmerch-prod
```

And then set:
- `VPS_DEPLOY_PATH=/srv/xmerch-prod`

## 5. GitHub Actions Access Setup

## 5.1 SSH Key For GitHub Actions

On your local machine:

```bash
ssh-keygen -t ed25519 -C "github-actions-xmerch" -f ./xmerch_actions_key
```

- Put `xmerch_actions_key` (private key content) into GitHub secret `VPS_SSH_KEY`.
- Add `xmerch_actions_key.pub` into VPS:

```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
cat >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

Paste public key and save.

## 5.2 GHCR Pull Token On VPS

Create GitHub token (classic PAT) with:
- `read:packages`

Set as secrets:
- `GHCR_USERNAME`
- `GHCR_TOKEN`

## 6. Configure GitHub Repository Secrets

Required secrets used by `C:/laragon/www/xmerch/.github/workflows/deploy-vps.yml`:

- `VPS_HOST` = your VPS IP
- `VPS_USER` = `deploy`
- `VPS_SSH_KEY` = private key content
- `VPS_PORT` = `22`
- `VPS_DEPLOY_PATH` = `/opt/xmerch`

- `XMERCH_COMPOSE_PROJECT` = `xmerch` (or your custom stack name)
- `XMERCH_APP_HTTP_PORT` = `127.0.0.1:8088` (or another localhost bind/port)
- `XMERCH_DB_DATABASE` = `xmerch`
- `XMERCH_DB_USERNAME` = `xmerch`
- `XMERCH_DB_PASSWORD` = strong password
- `XMERCH_DB_ROOT_PASSWORD` = strong password

- `GHCR_USERNAME` = GitHub username
- `GHCR_TOKEN` = PAT with `read:packages`

- `XMERCH_ENV_PRODUCTION` = full `.env` content (single secret)

## 6.1 Example `XMERCH_ENV_PRODUCTION` Content

Use `C:/laragon/www/xmerch/deploy/production/.env.production.example` as base. Example:

```env
APP_NAME=XMerch
APP_ENV=production
APP_KEY=base64:GENERATE_REAL_KEY
APP_DEBUG=false
APP_URL=https://xmerch.run.place
ASSET_URL=

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=xmerch
DB_USERNAME=xmerch
DB_PASSWORD=strong-db-password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-user
MAIL_PASSWORD=your-pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@xmerch.run.place
MAIL_FROM_NAME="XMerch"
```

Generate APP_KEY locally if needed:

```bash
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

## 7. Domain Setup (OVH + DNS)

You said the domain in use is `xmerch.run.place` from DNSExit.

So use one of these paths:

## 7.1 If DNS Is Managed In DNSExit (your current case)

Create/verify:
- `A` record: `xmerch.run.place` -> `YOUR_VPS_IPV4`
- Optional `AAAA` record for IPv6 if your VPS has stable IPv6.

You do not need OVH DNS zone changes for this domain when DNSExit is authoritative.

## 7.2 If DNS Is Managed In OVH (alternative path)

In OVH Manager:
1. Open domain DNS zone.
2. Add `A` record:
   - subdomain: `xmerch` (or root if needed)
   - target: VPS IPv4
3. Save and wait propagation.

## 8. Nginx Reverse Proxy For XMerch

Create Nginx site:

```bash
cat > /etc/nginx/sites-available/xmerch.run.place <<'NGINX'
server {
    listen 80;
    server_name xmerch.run.place;

    location / {
        proxy_pass http://127.0.0.1:8088;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
NGINX

ln -sf /etc/nginx/sites-available/xmerch.run.place /etc/nginx/sites-enabled/xmerch.run.place
nginx -t
systemctl reload nginx
```

This does not interfere with Spring Boot if Spring uses another domain/server block.

For a different production domain/port/folder:
- Replace `server_name` with your production domain.
- Replace `proxy_pass` target port with your `XMERCH_APP_HTTP_PORT`.

## 9. TLS (Let's Encrypt)

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d xmerch.run.place --redirect -m you@example.com --agree-tos -n
```

Validate auto-renew:

```bash
systemctl status certbot.timer
certbot renew --dry-run
```

## 10. First Deployment

1. Push branch matching workflow trigger (`main`, `master`, or `release/*`), for example:
   - `release/28_03_2026`
2. Open GitHub Actions and run `Deploy XMerch To VPS`.
3. Workflow will:
   - build Docker image
   - push image to GHCR
   - copy stack files to VPS
   - run `docker compose pull && up -d`
   - run Laravel migrations and caches

## 10.1 Backup Current Local Database

Run from Windows machine:

```powershell
powershell -ExecutionPolicy Bypass -File C:\laragon\www\xmerch\deploy\scripts\backup-local-db.ps1 -ProjectRoot C:\laragon\www\xmerch\project -ZipOutput
```

Backup files are generated under:
- `C:/laragon/www/xmerch/project/storage/backups`

## 10.2 Upload Backup To VPS

Example:

```bash
scp C:\path\to\xmerch_local_YYYYMMDD_HHMMSS.zip deploy@<VPS_IP>:/tmp/
```

## 10.3 Restore Backup Into VPS Database

On VPS:

```bash
chmod +x /opt/xmerch/scripts/restore-db-on-vps.sh || true
bash /opt/xmerch/scripts/restore-db-on-vps.sh /tmp/xmerch_local_YYYYMMDD_HHMMSS.zip /opt/xmerch/production
```

If your deployment folder is custom, pass it as second argument.

## 10.4 Post-Restore Sync

After restore:

```bash
cd <VPS_DEPLOY_PATH>/production
docker compose --project-name <XMERCH_COMPOSE_PROJECT> --env-file .stack.env -f docker-compose.yml exec -T web php artisan migrate --force
docker compose --project-name <XMERCH_COMPOSE_PROJECT> --env-file .stack.env -f docker-compose.yml exec -T web php artisan optimize:clear
docker compose --project-name <XMERCH_COMPOSE_PROJECT> --env-file .stack.env -f docker-compose.yml exec -T web php artisan config:cache
docker compose --project-name <XMERCH_COMPOSE_PROJECT> --env-file .stack.env -f docker-compose.yml exec -T web php artisan route:cache
docker compose --project-name <XMERCH_COMPOSE_PROJECT> --env-file .stack.env -f docker-compose.yml exec -T web php artisan view:cache
```

## 11. Verification Checklist

On VPS:

```bash
cd /opt/xmerch/production
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml ps
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml logs -f web
```

From browser:
- `https://xmerch.run.place` opens.
- Login works.
- Admin pages load.

From app:
- Confirm uploads write into Docker volume.
- Confirm checkout flow uses production URLs.

## 12. Optional: Enable Workers

If you use queues/scheduler in production:

```bash
cd /opt/xmerch/production
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml --profile workers up -d
```

## 13. Rollback Procedure

If a deploy fails:

1. Find previous image tag (commit SHA) in GHCR.
2. Edit `.stack.env` on VPS and set:
   - `APP_IMAGE=ghcr.io/<owner>/<repo>:<older_sha>`
3. Recreate services:

```bash
cd /opt/xmerch/production
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml pull
docker compose --project-name xmerch --env-file .stack.env -f docker-compose.yml up -d
```

## 14. Common Errors

- `419 Page Expired`:
  - Check `APP_URL` is `https://xmerch.run.place`
  - `SESSION_SECURE_COOKIE=true`
  - Nginx forwards `X-Forwarded-Proto`

- `SQL column not found`:
  - migrations missing, run:
  - `docker compose ... exec -T web php artisan migrate --force`

- Cannot pull image from GHCR:
  - verify `GHCR_USERNAME` + `GHCR_TOKEN`
  - token has `read:packages`

- DNS not resolving:
  - verify `A` record and wait propagation.
