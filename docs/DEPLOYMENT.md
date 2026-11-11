# Production Deployment & Operations Guide

This guide describes the complete production deployment process for **Revista GEN** (Open Journal Systems 2.4.x) running on Docker (PHP 5.6 Apache and MySQL 5.7) hosted on AWS Lightsail behind Amazon CloudFront.

---

## 1. Architecture Overview

```
                      HTTPS (TLS 1.2/1.3)
[ Client Browser ] ─────────────────────────> [ Amazon CloudFront CDN ]
                                                         │
                                                         │ HTTP (Keep-Alive)
                                                         ▼
                                            [ AWS Lightsail VPS Origin ]
                                                         │
                                        ┌────────────────┴────────────────┐
                                        │ Docker Stack (/home/ubuntu/...)  │
                                        │                                 │
                                        │  ┌───────────────────────────┐  │
                                        │  │ revista_ojs_app (Apache)  │  │
                                        │  │ Port 8080 (PHP 5.6)       │  │
                                        │  └─────────────┬─────────────┘  │
                                        │                │ Internal Net   │
                                        │  ┌─────────────┴─────────────┐  │
                                        │  │ revista_ojs_db (MySQL 5.7)│  │
                                        │  │ Port 3306 (127.0.0.1)     │  │
                                        │  └───────────────────────────┘  │
                                        └─────────────────────────────────┘
```

- **CloudFront CDN**: Terminates SSL/TLS, manages edge caching, and forwards headers (`X-Forwarded-Proto`, `Via`, `X-Amz-Cf-Id`).
- **Web Origin Container (`revista_ojs_app`)**: Apache 2.4 with PHP 5.6 serving the OJS application and custom Revista theme.
- **Database Container (`revista_ojs_db`)**: MySQL 5.7 bound to `127.0.0.1:3306` with persistent storage in `./mysql_data`.
- **Warm-Up Keep-Alive Ping (`scripts/keepalive-ping.sh`)**: Cronjob / timer maintaining edge-to-origin TCP keep-alive, pre-warming Apache prefork workers, and priming MySQL query caches.

---

## 2. Server Prerequisites

On the AWS Lightsail instance (Ubuntu 22.04 LTS):

1. **Docker Engine & Docker Compose**:
   ```bash
   sudo apt-get update
   sudo apt-get install -y docker.io docker-compose-plugin curl rsync
   sudo usermod -aG docker ubuntu
   # Re-login or execute: newgrp docker
   ```

2. **Clone the Repository**:
   ```bash
   git clone https://github.com/ChuyB/revista_gastro_vzla /home/ubuntu/revistag
   cd /home/ubuntu/revistag
   ```

---

## 3. Environment Configuration

Create and adjust `.env` from the provided `.env.example`:

```bash
cp .env.example .env
nano .env
```

Key configuration variables:

| Variable | Recommended Production Value | Note |
| :--- | :--- | :--- |
| `APP_PORT` | `8080` (or `80` if direct) | Port exposed by Apache container on host |
| `MYSQL_DATABASE` | `ojsg` | Production schema name |
| `MYSQL_USER` | `ojs_user` | Application database user |
| `MYSQL_PASSWORD` | `<secure-random-pass>` | Strong password for `ojs_user` |
| `MYSQL_ROOT_PASSWORD` | `<secure-random-root>` | Strong password for MySQL root |
| `DB_BIND_IP` | `127.0.0.1` | **Security:** Never expose MySQL publicly |
| `APP_BASE_URL` | `https://revistagen.com` | Base public URL matching CloudFront / custom domain |
| `ALLOWED_HOSTS` | `*.cloudfront.net,revistagen.com,localhost,127.0.0.1` | Hostname whitelist for Apache hardening |
| `REQUIRE_CLOUDFRONT`| `true` | Restricts origin traffic to CloudFront requests only |

Also verify `ojs_code/config.inc.php`:
- Ensure `base_url` matches `APP_BASE_URL`.
- Ensure `session_check_ip = Off` (required for CloudFront dynamic edge IP routing).

---

## 4. Data Transfer & Deployment

### 4.1 Sync Data from Local Development Workstation

Transfer private data (`ojs_files/` and `init_db/` SQL dump) to the server:

```bash
# From local workstation:
./scripts/sync-data.sh --ssh ubuntu@<lightsail-vps-ip>:/home/ubuntu/revistag
```

### 4.2 Run Automated Deployment on VPS

On the Lightsail VPS:

```bash
cd /home/ubuntu/revistag
./scripts/deploy.sh
```

`deploy.sh` automatically performs:
1. System prerequisite validation (Docker, Compose, directories).
2. Starting containers via `docker compose up -d --build`.
3. Waiting for MySQL health and table schema initialization.
4. Applying idempotent theme migration (`docs/production-db.sql`).
5. Clearing compiled Smarty templates and OJS file cache (`cache/fc-*`).
6. Running diagnostic verification suite (`docs/check-production.sql`).

---

## 5. Keep-Alive Warm-Up Ping Cronjob

### 5.1 Why the Warm-Up Ping Is Required

In production behind CloudFront:
1. **Edge-to-Origin Keep-Alive Expiration**: CloudFront terminates idle keep-alive connections to origins after brief timeouts. The next incoming user request must establish a fresh TCP 3-way handshake and TLS session.
2. **Cold Apache Worker Processes**: Apache prefork child workers idle out or terminate, requiring fork cycles upon new traffic bursts.
3. **Cold Database Buffer**: MySQL InnoDB buffer pool and query cache require priming after periods of silence.

Without active warming, cold requests experience high latency spikes. Running `scripts/keepalive-ping.sh` every 2 minutes ensures connections remain warm and responsive.

### 5.2 Crontab Setup (Recommended)

1. Open crontab on the Lightsail VPS:
   ```bash
   crontab -e
   ```

2. Add the scheduled ping:
   ```cron
   */2 * * * * /home/ubuntu/revistag/scripts/keepalive-ping.sh >/dev/null 2>&1
   ```

3. Confirm the installed job:
   ```bash
   crontab -l
   ```

### 5.3 Testing & Verification (`-v` / `--verbose`)

Verify the endpoint and view DNS, connection, and TLS timing statistics:

```bash
# Ping the default configured CloudFront endpoint:
/home/ubuntu/revistag/scripts/keepalive-ping.sh -v

# Or ping a specific domain / origin:
/home/ubuntu/revistag/scripts/keepalive-ping.sh https://d3y1qikecd1br.cloudfront.net/ -v
```

Output format:
```
[2026-11-11T13:50:00Z] Ping https://d3y1qikecd1br.cloudfront.net/ -> HTTP 200 | DNS: 0.0042s | Connect: 0.0215s | TLS: 0.0451s | Total: 0.1287s
```

### 5.4 Alternative: Systemd Timer

If preferred over cron for central `journald` logging:

1. Create `/etc/systemd/system/revistag-keepalive.service`:
   ```ini
   [Unit]
   Description=RevistaG CloudFront Keep-Alive Warm-Up Ping
   After=network-online.target
   Wants=network-online.target

   [Service]
   Type=oneshot
   User=ubuntu
   WorkingDirectory=/home/ubuntu/revistag
   ExecStart=/home/ubuntu/revistag/scripts/keepalive-ping.sh
   ```

2. Create `/etc/systemd/system/revistag-keepalive.timer`:
   ```ini
   [Unit]
   Description=Run RevistaG Keep-Alive Ping every 2 minutes

   [Timer]
   OnBootSec=1min
   OnUnitActiveSec=2min
   AccuracySec=5s

   [Install]
   WantedBy=timers.target
   ```

3. Enable and activate the timer:
   ```bash
   sudo systemctl daemon-reload
   sudo systemctl enable --now revistag-keepalive.timer
   ```

4. Verify status:
   ```bash
   systemctl list-timers revistag-keepalive.timer
   journalctl -u revistag-keepalive.service -n 20
   ```

---

## 6. Verification Checklist

After deployment and cron setup, verify:

- [ ] Web application returns HTTP 200 via CloudFront URL: `curl -I https://<distribution>.cloudfront.net/`
- [ ] Direct unauthenticated non-CloudFront requests receive 403 (when `REQUIRE_CLOUDFRONT=true`)
- [ ] OJS theme renders two-column layout with Revista GEN styling
- [ ] Floating chatbot button displays correctly on mobile (<801px) and inline on desktop
- [ ] Verification suite passes:
  ```bash
  docker exec -i revista_ojs_db mysql -u ojs_user -p$(grep MYSQL_PASSWORD .env | cut -d= -f2) ojsg < docs/check-production.sql
  ```
- [ ] Keep-alive cron is running and healthy:
  ```bash
  /home/ubuntu/revistag/scripts/keepalive-ping.sh -v
  ```
