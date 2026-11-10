#!/usr/bin/env bash
# ==============================================================================
# scripts/sync-data.sh
# 
# Prepares and packages/syncs non-git production data:
#   - init_db/ (01_latest_dump.sql.gz + 02_production-db.sql)
#   - ojs_files/ (article PDFs, galleys, logs, ~1.9 GB)
#   - Optional production config.inc.php
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
    echo -e "  --archive [FILE]              Create a compressed archive (default: deploy-data.tar.gz)"
    echo -e "  --ssh <user@host:/path>       Directly rsync data to remote server directory"
    echo -e "  -h, --help                    Show this help message"
    echo -e ""
    echo -e "${BOLD}Examples:${NC}"
    echo -e "  $0 --archive"
    echo -e "  $0 --archive my-deploy.tar.gz"
    echo -e "  $0 --ssh ubuntu@52.205.19.85:/var/www/revistag"
    echo -e "  $0 --ssh deploy@revistagen.com:/var/www/revistag"
    exit "${exit_code}"
}

MODE="archive"
ARCHIVE_NAME="deploy-data.tar.gz"
REMOTE_TARGET=""

while [[ $# -gt 0 ]]; do
    case "$1" in
        --archive)
            MODE="archive"
            if [[ $# -gt 1 && ! "$2" =~ ^-- ]]; then
                ARCHIVE_NAME="$2"
                shift
            fi
            shift
            ;;
        --ssh)
            MODE="ssh"
            if [[ $# -gt 1 && ! "$2" =~ ^-- ]]; then
                REMOTE_TARGET="$2"
                shift
            else
                log_error "Missing argument for --ssh. Expected user@host:/path/to/revistag"
                exit 1
            fi
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

# Validate SSH destination format
if [[ "${MODE}" == "ssh" ]]; then
    if [[ "${REMOTE_TARGET}" != *:* || -z "${REMOTE_TARGET%%:*}" || -z "${REMOTE_TARGET#*:}" ]]; then
        log_error "Invalid target format for --ssh: '${REMOTE_TARGET}'"
        log_error "SSH target must include host and destination directory path separated by ':'"
        echo -e "\n${BOLD}Correct usage:${NC}"
        echo -e "  $0 --ssh user@host:/path/to/revistag"
        echo -e "  $0 --ssh ubuntu@52.205.19.85:/var/www/revistag"
        exit 1
    fi
fi

echo -e "${BOLD}====================================================================${NC}"
echo -e "${BOLD}   Revista GEN - Production Data Sync / Package Tool               ${NC}"
echo -e "${BOLD}====================================================================${NC}"

# ------------------------------------------------------------------------------
# 1. Prepare init_db/
# ------------------------------------------------------------------------------
log_info "Preparing init_db/..."
mkdir -p "${ROOT_DIR}/init_db"

# Copy companion production migration SQL as 02_production-db.sql
if [[ -f "${ROOT_DIR}/docs/production-db.sql" ]]; then
    log_info "Copying docs/production-db.sql -> init_db/02_production-db.sql"
    cp "${ROOT_DIR}/docs/production-db.sql" "${ROOT_DIR}/init_db/02_production-db.sql"
elif [[ -f "${ROOT_DIR}/updates/production-db.sql" ]]; then
    log_info "Copying updates/production-db.sql -> init_db/02_production-db.sql"
    cp "${ROOT_DIR}/updates/production-db.sql" "${ROOT_DIR}/init_db/02_production-db.sql"
fi

# Check for latest_dump.sql and compress if not already compressed
if [[ -f "${ROOT_DIR}/init_db/latest_dump.sql" ]]; then
    if [[ ! -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" || \
          "${ROOT_DIR}/init_db/latest_dump.sql" -nt "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" ]]; then
        log_info "Compressing init_db/latest_dump.sql -> init_db/01_latest_dump.sql.gz..."
        gzip -c -9 "${ROOT_DIR}/init_db/latest_dump.sql" > "${ROOT_DIR}/init_db/01_latest_dump.sql.gz"
        log_success "Compressed database dump ($(du -h "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" | cut -f1))"
    fi
    log_info "Removing uncompressed init_db/latest_dump.sql to prevent duplicate import in /docker-entrypoint-initdb.d..."
    rm -f "${ROOT_DIR}/init_db/latest_dump.sql"
fi

if [[ ! -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" && ! -f "${ROOT_DIR}/init_db/latest_dump.sql" ]]; then
    log_warn "No database dump found in init_db/. (Expected latest_dump.sql or 01_latest_dump.sql.gz)"
fi

# ------------------------------------------------------------------------------
# 2. Check ojs_files/
# ------------------------------------------------------------------------------
if [[ ! -d "${ROOT_DIR}/ojs_files" ]]; then
    log_error "Directory ojs_files/ does not exist. Aborting."
    exit 1
fi
log_info "Found ojs_files/ ($(du -sh "${ROOT_DIR}/ojs_files" | cut -f1))"

# ------------------------------------------------------------------------------
# 3. Perform Sync or Packaging
# ------------------------------------------------------------------------------
if [[ "${MODE}" == "ssh" ]]; then
    log_info "Syncing data to remote target: ${REMOTE_TARGET} via rsync..."
    
    # Make sure remote directories exist
    ssh "${REMOTE_TARGET%%:*}" "mkdir -p '${REMOTE_TARGET#*:}/init_db' '${REMOTE_TARGET#*:}/ojs_files'"
    
    # Rsync init_db (excluding uncompressed latest_dump.sql if gzip exists)
    log_info "Syncing init_db/..."
    rsync -avz --progress \
        --exclude='latest_dump.sql' \
        "${ROOT_DIR}/init_db/" "${REMOTE_TARGET}/init_db/"
    
    # Rsync ojs_files
    log_info "Syncing ojs_files/ (this may take a few minutes on first run)..."
    rsync -avz --progress \
        --exclude='.DS_Store' \
        "${ROOT_DIR}/ojs_files/" "${REMOTE_TARGET}/ojs_files/"
        
    log_success "Rsync completed successfully!"
    echo -e "\nNext step on the server (${REMOTE_TARGET%%:*}):"
    echo -e "  cd ${REMOTE_TARGET#*:}"
    echo -e "  ./scripts/deploy.sh"

else
    log_info "Creating archive: ${ARCHIVE_NAME}..."
    
    # Package init_db and ojs_files
    # Exclude uncompressed latest_dump.sql if 01_latest_dump.sql.gz exists to save space
    TAR_EXCLUDES=(--exclude='.DS_Store')
    if [[ -f "${ROOT_DIR}/init_db/01_latest_dump.sql.gz" ]]; then
        TAR_EXCLUDES+=(--exclude='init_db/latest_dump.sql')
    fi
    
    tar -czf "${ARCHIVE_NAME}" "${TAR_EXCLUDES[@]}" init_db ojs_files
    
    log_success "Created archive: ${ARCHIVE_NAME} ($(du -h "${ARCHIVE_NAME}" | cut -f1))"
    echo -e "\n${BOLD}How to deploy with this archive:${NC}"
    echo -e "  1. Copy archive to your server:"
    echo -e "     ${CYAN}scp ${ARCHIVE_NAME} user@your-server:/path/to/revistag/${NC}"
    echo -e "  2. On the server, extract it:"
    echo -e "     ${CYAN}tar -xzf ${ARCHIVE_NAME}${NC}"
    echo -e "  3. Run the deployment script:"
    echo -e "     ${CYAN}./scripts/deploy.sh${NC}"
fi
