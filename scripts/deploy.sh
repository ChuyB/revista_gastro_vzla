#!/usr/bin/env bash
# ==============================================================================
# scripts/deploy.sh
# 
# Production deployment, startup, and validation script.
# Run this script on the production server inside the revistag repository.
# ==============================================================================
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

cd "${ROOT_DIR}"

# ANSI Colors
GREEN="\033[0;32m"
YELLOW="\033[1;33m"
CYAN="\033[0;36m"
RED="\033[0;31m"
BOLD="\033[1m"
NC="\033[0m"

log_info()    { echo -e "${CYAN}[INFO]${NC} $*"; }
log_success() { echo -e "${GREEN}[OK]${NC} $*"; }
log_warn()    { echo -e "${YELLOW}[WARN]${NC} $*"; }
log_error()   { echo -e "${RED}[ERROR]${NC} $*"; }

echo -e "${BOLD}====================================================================${NC}"
echo -e "${BOLD}   Revista GEN - Production Deployment & Startup Script            ${NC}"
echo -e "${BOLD}====================================================================${NC}"

# ------------------------------------------------------------------------------
# 1. Check prerequisites
# ------------------------------------------------------------------------------
log_info "Checking system requirements..."

if ! command -v docker >/dev/null 2>&1; then
    log_error "Docker is not installed. Please install Docker first."
    exit 1
fi

DOCKER_COMPOSE_CMD=""
if docker compose version >/dev/null 2>&1; then
    DOCKER_COMPOSE_CMD="docker compose"
elif command -v docker-compose >/dev/null 2>&1; then
    DOCKER_COMPOSE_CMD="docker-compose"
else
    log_error "Neither 'docker compose' nor 'docker-compose' found. Please install Docker Compose."
    exit 1
fi
log_success "Docker & Docker Compose available (${DOCKER_COMPOSE_CMD})."

# ------------------------------------------------------------------------------
# 2. Check .env file
# ------------------------------------------------------------------------------
if [[ ! -f "${ROOT_DIR}/.env" ]]; then
    if [[ -f "${ROOT_DIR}/.env.example" ]]; then
        log_warn ".env file not found. Creating from .env.example..."
        cp "${ROOT_DIR}/.env.example" "${ROOT_DIR}/.env"
        log_warn "Created .env with default values. Please review it if using custom ports or passwords."
    else
        log_warn "No .env or .env.example found. Continuing with compose defaults."
    fi
fi

# Load .env variables for script usage if available
if [[ -f "${ROOT_DIR}/.env" ]]; then
    # export non-commented vars
    set -a
    # shellcheck disable=SC1091
    source "${ROOT_DIR}/.env"
    set +a
fi

DB_USER="${MYSQL_USER:-ojs_user}"
DB_PASS="${MYSQL_PASSWORD:-ojs_password}"
DB_NAME="${MYSQL_DATABASE:-ojsg}"
DB_ROOT_PASS="${MYSQL_ROOT_PASSWORD:-root_password}"

# ------------------------------------------------------------------------------
# 3. Check data directories
# ------------------------------------------------------------------------------
log_info "Checking required directories..."

if [[ ! -d "${ROOT_DIR}/ojs_code" ]]; then
    log_error "ojs_code/ directory is missing! Git clone may be incomplete."
    exit 1
fi

if [[ ! -d "${ROOT_DIR}/ojs_files" ]]; then
    log_error "ojs_files/ directory is missing! Please extract or sync ojs_files first."
    log_error "Run ./scripts/sync-data.sh on your source machine."
    exit 1
fi

# Create cache and public directories if missing
mkdir -p "${ROOT_DIR}/ojs_code/cache/t_compile" \
         "${ROOT_DIR}/ojs_code/cache/t_cache" \
         "${ROOT_DIR}/ojs_code/public"

# ------------------------------------------------------------------------------
# 4. Check config.inc.php base_url notice
# ------------------------------------------------------------------------------
if [[ -f "${ROOT_DIR}/ojs_code/config.inc.php" ]]; then
    if grep -q "http://localhost:8080" "${ROOT_DIR}/ojs_code/config.inc.php"; then
        log_warn "Notice: ojs_code/config.inc.php contains 'http://localhost:8080'."
        log_warn "If this is a public production domain, make sure to update 'base_url' in ojs_code/config.inc.php"
    fi
fi

# ------------------------------------------------------------------------------
# 5. Build and start containers
# ------------------------------------------------------------------------------
log_info "Starting Docker containers with ${DOCKER_COMPOSE_CMD}..."
${DOCKER_COMPOSE_CMD} up -d --build

# ------------------------------------------------------------------------------
# 6. Wait for MySQL to become ready
# ------------------------------------------------------------------------------
log_info "Waiting for database container to initialize and become ready..."
MAX_ATTEMPTS=60
ATTEMPT=0
DB_READY=false

while [[ ${ATTEMPT} -lt ${MAX_ATTEMPTS} ]]; do
    ATTEMPT=$((ATTEMPT + 1))
    if docker exec revista_ojs_db mysqladmin ping -u root -p"${DB_ROOT_PASS}" --silent >/dev/null 2>&1; then
        DB_READY=true
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

if [[ "${DB_READY}" != "true" ]]; then
    log_error "Database did not become ready after $((MAX_ATTEMPTS * 2)) seconds."
    log_error "Check container logs: docker logs revista_ojs_db"
    exit 1
fi
log_success "Database is ready!"

# ------------------------------------------------------------------------------
# 7. Apply production-db.sql if needed
# ------------------------------------------------------------------------------
SQL_FILE=""
if [[ -f "${ROOT_DIR}/docs/production-db.sql" ]]; then
    SQL_FILE="${ROOT_DIR}/docs/production-db.sql"
elif [[ -f "${ROOT_DIR}/updates/production-db.sql" ]]; then
    SQL_FILE="${ROOT_DIR}/updates/production-db.sql"
fi

if [[ -n "${SQL_FILE}" ]]; then
    log_info "Applying database settings from ${SQL_FILE}..."
    docker exec -i revista_ojs_db mysql -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < "${SQL_FILE}"
    log_success "Database updates applied."
fi

# ------------------------------------------------------------------------------
# 8. Clear OJS Cache (fc-*.php and t_compile)
# ------------------------------------------------------------------------------
log_info "Clearing OJS file and template caches..."
docker exec revista_ojs_app sh -c "find /var/www/html/cache -maxdepth 1 -name 'fc-*' -delete && rm -rf /var/www/html/cache/t_compile/*"
log_success "OJS cache cleared."

# ------------------------------------------------------------------------------
# 9. Run Verification Check (check-production.sql)
# ------------------------------------------------------------------------------
CHECK_SQL=""
if [[ -f "${ROOT_DIR}/docs/check-production.sql" ]]; then
    CHECK_SQL="${ROOT_DIR}/docs/check-production.sql"
elif [[ -f "${ROOT_DIR}/updates/check-production.sql" ]]; then
    CHECK_SQL="${ROOT_DIR}/updates/check-production.sql"
fi

if [[ -n "${CHECK_SQL}" ]]; then
    echo ""
    log_info "Running production verification checks..."
    docker exec -i revista_ojs_db mysql -u "${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < "${CHECK_SQL}" || true
fi

# ------------------------------------------------------------------------------
# 10. Summary
# ------------------------------------------------------------------------------
echo ""
echo -e "${BOLD}====================================================================${NC}"
log_success "Deployment completed successfully!"
echo -e "${BOLD}Container Status:${NC}"
${DOCKER_COMPOSE_CMD} ps

APP_PORT_DISPLAY="${APP_PORT:-8080}"
echo -e "\n${BOLD}Website is running at:${NC} http://localhost:${APP_PORT_DISPLAY}/"
echo -e "${BOLD}====================================================================${NC}"
