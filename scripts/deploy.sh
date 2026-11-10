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

usage() {
    local exit_code="${1:-0}"
    echo -e "${BOLD}Usage:${NC}"
    echo -e "  $0 [options]"
    echo -e ""
    echo -e "${BOLD}Options:${NC}"
    echo -e "  --fresh-db, --reset-db    Wipe ./mysql_data before startup to re-import database from init_db/"
    echo -e "  -y, --yes                 Non-interactive mode (automatically confirm prompts)"
    echo -e "  -h, --help                Show this help message"
    echo -e ""
    echo -e "${BOLD}Examples:${NC}"
    echo -e "  $0"
    echo -e "  $0 --fresh-db"
    echo -e "  $0 --fresh-db -y"
    exit "${exit_code}"
}

FRESH_DB=false
ASSUME_YES=false

while [[ $# -gt 0 ]]; do
    case "$1" in
        --fresh-db|--reset-db)
            FRESH_DB=true
            shift
            ;;
        -y|--yes)
            ASSUME_YES=true
            shift
            ;;
        -h|--help)
            usage 0
            ;;
        *)
            log_error "Unknown option: $1"
            usage 1
            ;;
    esac
done

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

# Check init_db/ dump file presence
if [[ ! -d "${ROOT_DIR}/init_db" || ( ! -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" && ! -f "${ROOT_DIR}/init_db/latest_dump.sql" ) ]]; then
    if [[ ! -d "${ROOT_DIR}/mysql_data" || -z "$(ls -A "${ROOT_DIR}/mysql_data" 2>/dev/null)" ]]; then
        log_warn "No database dump found in init_db/ (expected 01_latest_dump.sql.gz)!"
        log_warn "If ./mysql_data is initialized empty, OJS tables will not be created."
    fi
fi

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
# 5. Handle --fresh-db / --reset-db if requested
# ------------------------------------------------------------------------------
if [[ "${FRESH_DB}" == "true" ]]; then
    # Prevent data loss if no dump exists to re-import from
    if [[ ! -d "${ROOT_DIR}/init_db" || ( ! -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" && ! -f "${ROOT_DIR}/init_db/latest_dump.sql" ) ]]; then
        log_error "Database reset requested (--fresh-db), but no database dump found in init_db/!"
        log_error "Expected init_db/01_latest_dump.sql.gz or init_db/latest_dump.sql."
        log_error "Aborting before stopping containers or touching ./mysql_data to prevent data loss."
        exit 1
    fi

    echo -e "${YELLOW}====================================================================${NC}"
    echo -e "${YELLOW}[WARN] Database reset requested (--fresh-db / --reset-db).${NC}"
    echo -e "${YELLOW}This will completely remove ./mysql_data and re-import from init_db/.${NC}"
    echo -e "${YELLOW}====================================================================${NC}"
    if [[ "${ASSUME_YES}" != "true" ]]; then
        read -r -p "Are you sure you want to completely wipe ./mysql_data and re-import? [y/N] " CONFIRM
        if [[ ! "${CONFIRM}" =~ ^[yY]([eE][sS])?$ ]]; then
            log_info "Database reset cancelled by user."
            exit 0
        fi
    fi

    log_info "Stopping existing Docker containers..."
    ${DOCKER_COMPOSE_CMD} down 2>/dev/null || true

    log_info "Wiping ./mysql_data..."
    if [[ -d "${ROOT_DIR}/mysql_data" ]]; then
        # Try direct removal
        if ! rm -rf "${ROOT_DIR}/mysql_data"/* "${ROOT_DIR}/mysql_data"/.[!.]* 2>/dev/null; then
            # If files are owned by container's mysql user, remove via temporary container
            if docker run --rm -v "${ROOT_DIR}/mysql_data":/target mysql:5.7 sh -c 'rm -rf /target/* /target/.[!.]*' 2>/dev/null; then
                log_success "Cleaned ./mysql_data via Docker container."
            else
                log_warn "Could not completely clean ./mysql_data due to file permissions."
                log_warn "Please run: sudo rm -rf ./mysql_data/*"
                exit 1
            fi
        else
            log_success "Cleaned ./mysql_data."
        fi
    fi
    mkdir -p "${ROOT_DIR}/mysql_data"
fi

# ------------------------------------------------------------------------------
# 6. Build and start containers
# ------------------------------------------------------------------------------
# Clean up redundant uncompressed dump to prevent duplicate import in /docker-entrypoint-initdb.d
if [[ -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" && -f "${ROOT_DIR}/init_db/latest_dump.sql" ]]; then
    log_info "Found both 01_latest_dump.sql.gz and latest_dump.sql in init_db/."
    log_info "Removing redundant latest_dump.sql to prevent duplicate import..."
    rm -f "${ROOT_DIR}/init_db/latest_dump.sql"
fi

log_info "Starting Docker containers with ${DOCKER_COMPOSE_CMD}..."
${DOCKER_COMPOSE_CMD} up -d --build

# ------------------------------------------------------------------------------
# 7. Wait for MySQL to initialize, import database, and become ready
# ------------------------------------------------------------------------------
log_info "Waiting for database server to respond to ping..."
MAX_PING_ATTEMPTS=45
PING_ATTEMPT=0
DB_PING_OK=false

while [[ ${PING_ATTEMPT} -lt ${MAX_PING_ATTEMPTS} ]]; do
    PING_ATTEMPT=$((PING_ATTEMPT + 1))
    if docker exec revista_ojs_db mysqladmin ping -u root -p"${DB_ROOT_PASS}" --silent >/dev/null 2>&1; then
        DB_PING_OK=true
        break
    fi
    echo -n "."
    sleep 2
done
echo ""

if [[ "${DB_PING_OK}" != "true" ]]; then
    log_error "Database container failed to respond to ping after $((MAX_PING_ATTEMPTS * 2)) seconds."
    log_error "Check container logs: docker logs revista_ojs_db"
    exit 1
fi

get_table_count() {
    local count
    count=$(docker exec revista_ojs_db mysql -u root -p"${DB_ROOT_PASS}" -s -N \
        -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '${DB_NAME}';" 2>/dev/null) || count="-1"
    count="${count//[[:space:]]/}"
    if [[ -z "${count}" ]]; then
        count="-1"
    fi
    echo "${count}"
}

check_journals_table() {
    docker exec revista_ojs_db mysql -u root -p"${DB_ROOT_PASS}" \
        -e "SELECT 1 FROM ${DB_NAME}.journals LIMIT 1;" >/dev/null 2>&1
}

# In mysql:5.7, entrypoint runs initdb scripts on a temporary daemon.
# We must wait until the SQL dump finishes importing and tables are accessible.
DB_TIMEOUT=180
ELAPSED=0
WAIT_INTERVAL=3
DB_READY=false

log_info "Waiting for database initialization and table import to complete (timeout: ${DB_TIMEOUT}s)..."

while [[ ${ELAPSED} -lt ${DB_TIMEOUT} ]]; do
    LOGS=$(docker logs revista_ojs_db 2>&1 || true)

    # Check if entrypoint ran / is running initdb scripts in this container
    HAS_INIT_STARTED=false
    if echo "${LOGS}" | grep -q "/docker-entrypoint-initdb.d"; then
        HAS_INIT_STARTED=true
    fi

    HAS_INIT_FINISHED=false
    if echo "${LOGS}" | grep -q "MySQL init process done"; then
        HAS_INIT_FINISHED=true
    fi

    # Check if journals table exists and responds
    if check_journals_table; then
        # If initdb was started, make sure the init process has finished so we are on the permanent server
        if [[ "${HAS_INIT_STARTED}" == "true" && "${HAS_INIT_FINISHED}" != "true" ]]; then
            echo -n "."
            sleep ${WAIT_INTERVAL}
            ELAPSED=$((ELAPSED + WAIT_INTERVAL))
            continue
        fi

        DB_READY=true
        break
    fi

    echo -n "."
    sleep ${WAIT_INTERVAL}
    ELAPSED=$((ELAPSED + WAIT_INTERVAL))
done
echo ""

if [[ "${DB_READY}" != "true" ]]; then
    log_error "Database initialization or import did not complete within ${DB_TIMEOUT} seconds."
    TABLE_COUNT=$(get_table_count)
    LOGS=$(docker logs revista_ojs_db 2>&1 || true)

    if [[ "${TABLE_COUNT}" == "0" ]]; then
        if ! echo "${LOGS}" | grep -q "/docker-entrypoint-initdb.d"; then
            log_error "Database volume ./mysql_data was previously initialized with 0 tables. MySQL skips /docker-entrypoint-initdb.d when ./mysql_data is already populated."
        else
            log_error "Database initialization completed, but 0 tables were created."
        fi
        log_error "To re-import, run: docker compose down && sudo rm -rf ./mysql_data/* && ./scripts/deploy.sh"
        echo -e "Alternatively, run: ${CYAN}./scripts/deploy.sh --fresh-db${NC}"
    elif [[ "${TABLE_COUNT}" == "-1" ]]; then
        log_error "Database server did not become responsive to queries within ${DB_TIMEOUT} seconds."
        log_error "Check container logs: docker logs revista_ojs_db"
    else
        log_error "Database has ${TABLE_COUNT} tables, but 'journals' table is not accessible."
        log_error "Check container logs: docker logs revista_ojs_db"
    fi
    exit 1
fi

TABLE_COUNT=$(get_table_count)
log_success "Database is ready! (${TABLE_COUNT} tables found, 'journals' table verified)"

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
