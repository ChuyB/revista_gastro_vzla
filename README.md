# Revista GEN — OJS Docker Deployment

Open Journal Systems (OJS 2.4.x) deployment running on PHP 5.6 and MySQL 5.7 with Docker & Docker Compose. Includes the redesigned **Revista GEN** theme, block configurations, and automated data synchronization/deployment scripts.

---

## 1. Directory Structure & Data Separation

Due to the size and sensitivity of journal data, this repository strictly separates **version-controlled codebase** from **private/large binary data**:

```
.
├── Dockerfile                  # PHP 5.6 Apache image definition
├── docker-compose.yml          # Container stack orchestration (web + db)
├── entrypoint.sh               # Container startup script (permissions & dirs)
├── .env.example                # Template for environment configuration
├── .gitignore                  # Excludes private data, caches, dumps, and .env
│
├── ojs_code/                   # [GIT TRACKED] OJS core, plugins & custom theme
│   ├── config.inc.php          # Main OJS configuration file (base_url, DB, SMTP)
│   ├── plugins/themes/revista/ # Revista GEN custom theme
│   └── cache/                  # [GITIGNORED] Dynamic PHP cache (fc-*.php) & templates
│
├── ojs_files/                  # [GITIGNORED] Private data (~1.9 GB): PDFs, galleys, logs
├── init_db/                    # [GITIGNORED] Database initialization scripts
│   ├── 01_latest_dump.sql.gz   # Compressed production database dump (~9 MB)
│   └── 02_production-db.sql    # Companion theme & settings migration
│
├── mysql_data/                 # [GITIGNORED] Persistent MySQL container storage
│
├── docs/                       # Migration and documentation resources
│   ├── production-db.sql       # Idempotent DB migration for theme & blocks
│   ├── check-production.sql    # Read-only verification diagnostic suite
│   └── production-deploy.md    # Reference deployment documentation
│
└── scripts/                    # Deployment & synchronization automation tools
    ├── sync-data.sh            # Prepares, compresses, and transfers private data
    └── deploy.sh               # Production server startup, cache flush & verification
```

---

## 2. Environment Configuration (`.env`)

Configuration parameters (ports, passwords, URLs) are managed via `.env` rather than editing tracked Git files.

Create `.env` from the template:
```bash
cp .env.example .env
```

| Variable | Default (Dev) | Description / Production Recommendation |
| :--- | :--- | :--- |
| `APP_PORT` | `8080` | Host port for the web app (`80` for direct HTTP, or `8080` behind a reverse proxy like Nginx) |
| `MYSQL_DATABASE` | `ojsg` | Database schema name |
| `MYSQL_USER` | `ojs_user` | MySQL user name |
| `MYSQL_PASSWORD` | `ojs_password` | Set a secure database password |
| `MYSQL_ROOT_PASSWORD` | `root_password` | Set a secure MySQL root password |
| `DB_BIND_IP` | `127.0.0.1` | **Security:** Keep `127.0.0.1` so MySQL is NOT exposed publicly on the internet |
| `DB_PORT` | `3306` | Local host port bound to MySQL container |
| `APP_BASE_URL` | `http://localhost:8080` | Production domain (must match `base_url` in `config.inc.php`) |

---

## 3. Local Development Setup

To start the local development stack:

```bash
# 1. Start containers and run verification
./scripts/deploy.sh
```

- Web application: [http://localhost:8080](http://localhost:8080)
- Database: `127.0.0.1:3306` (accessible via MySQL client with credentials in `.env`)

---

## 4. Production Deployment Workflows

Because `ojs_files/` (~1.9 GB) and `init_db/` are not stored in GitHub, deploying to a new server requires transferring this private data alongside the cloned repository.

Choose one of the two supported deployment paths:

### Path A: Direct Sync via SSH / Rsync (Recommended)

This is the fastest method: it transfers files with on-the-fly compression, shows a live progress bar, and resumes automatically if interrupted.

#### On Local Machine:
```bash
# 1. Commit and push any code changes
git push origin main

# 2. Sync non-git data (init_db + ojs_files) to the server
./scripts/sync-data.sh --ssh user@your-server-ip:/var/www/revistag
```

#### On Production Server:
```bash
# 3. Clone repository (first time only)
git clone https://github.com/ChuyB/revista_gastro_vzla /var/www/revistag
cd /var/www/revistag

# 4. Configure environment
cp .env.example .env
nano .env  # set APP_PORT, secure passwords, and domain

# 5. Check ojs_code/config.inc.php
# Update `base_url` to match your public URL (e.g., https://revistagen.com)
nano ojs_code/config.inc.php

# 6. Execute deployment
./scripts/deploy.sh
```

---

### Path B: Manual Archive Transfer (`tar.gz`)

Use this method if the production server cannot be accessed directly via SSH/rsync from your workstation.

#### On Local Machine:
```bash
# 1. Package non-git data into a compressed archive (~700 MB)
./scripts/sync-data.sh --archive

# 2. Transfer the archive to the server
scp deploy-data.tar.gz user@your-server-ip:/var/www/revistag/
```

#### On Production Server:
```bash
# 3. Navigate to repository directory and extract archive
cd /var/www/revistag
tar -xzf deploy-data.tar.gz

# 4. Configure environment and config.inc.php
cp .env.example .env && nano .env
nano ojs_code/config.inc.php

# 5. Execute deployment
./scripts/deploy.sh
```

---

## 5. What `./scripts/deploy.sh` Does Automatically

Running `./scripts/deploy.sh` on the server performs the following sequence:

1. **Pre-flight Check**: Validates Docker, Docker Compose, `.env`, and required directories.
2. **Container Launch**: Runs `docker compose up -d --build`.
3. **Database Health Wait**: Polls MySQL until it is healthy and accepting connections.
4. **Idempotent DB Migration**: Executes `docs/production-db.sql` to ensure all Revista theme settings, block placements, and footer elements are up to date.
5. **Cache Invalidation**: Clears OJS cache files (`cache/fc-*.php`) and compiled Smarty templates (`cache/t_compile/*`) inside the container.
6. **Diagnostic Verification**: Runs `docs/check-production.sql`, printing a summary table where every check must read **`PASS`**.

---

## 6. Common Operations & Maintenance

### Clearing the OJS Cache
OJS caches site and plugin settings in flat PHP files. If you change a setting in the database and the site does not reflect it, clear the cache:

```bash
docker exec revista_ojs_app sh -c "find /var/www/html/cache -maxdepth 1 -name 'fc-*' -delete && rm -rf /var/www/html/cache/t_compile/*"
```
*(The cache files will regenerate automatically on the next page load).*

### Taking a Database Backup
```bash
docker exec revista_ojs_db mysqldump -u root -p$(grep MYSQL_ROOT_PASSWORD .env | cut -d= -f2) ojsg > backup_ojsg_$(date +%Y%m%d_%H%M%S).sql
```

### Running Verification Checks
To verify the database configuration state at any time:
```bash
docker exec -i revista_ojs_db mysql -u ojs_user -p$(grep MYSQL_PASSWORD .env | cut -d= -f2) ojsg < docs/check-production.sql
```

### Updating Code on Production
When pushing new code changes from development:
```bash
# On server:
git pull origin main
./scripts/deploy.sh
```
