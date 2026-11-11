#!/usr/bin/env bash
# ==============================================================================
# scripts/keepalive-ping.sh
#
# CloudFront & Origin Warm-Up / Keep-Alive Ping Script
#
# PURPOSE:
# Pings the CloudFront distribution / origin endpoint periodically (e.g. every 2 min)
# to keep edge-to-origin TCP keep-alive connections open, warm up Apache prefork
# workers, and eliminate initial request latency spikes.
#
# ==============================================================================
# SCHEDULING INSTRUCTIONS (LIGHTSAIL VPS)
# ==============================================================================
#
# Option A: Standard Crontab (Recommended & Simple)
# -------------------------------------------------
# 1. Open the crontab editor on the Lightsail VPS:
#      crontab -e
#
# 2. Add the following line to execute every 2 minutes:
#      */2 * * * * /home/ubuntu/revistag/scripts/keepalive-ping.sh >/dev/null 2>&1
#
# 3. Verify active crontab:
#      crontab -l
#
# ------------------------------------------------------------------------------
# Option B: Systemd Timer (Robust with journald logging)
# ------------------------------------------------------------------------------
# 1. Create the systemd service file:
#      sudo nano /etc/systemd/system/revistag-keepalive.service
#
#    Contents:
#    [Unit]
#    Description=RevistaG CloudFront Keep-Alive Warm-Up Ping
#    After=network-online.target
#    Wants=network-online.target
#
#    [Service]
#    Type=oneshot
#    User=ubuntu
#    WorkingDirectory=/home/ubuntu/revistag
#    ExecStart=/home/ubuntu/revistag/scripts/keepalive-ping.sh
#
# 2. Create the systemd timer file:
#      sudo nano /etc/systemd/system/revistag-keepalive.timer
#
#    Contents:
#    [Unit]
#    Description=Run RevistaG Keep-Alive Ping every 2 minutes
#
#    [Timer]
#    OnBootSec=1min
#    OnUnitActiveSec=2min
#    AccuracySec=5s
#
#    [Install]
#    WantedBy=timers.target
#
# 3. Reload systemd and enable the timer:
#      sudo systemctl daemon-reload
#      sudo systemctl enable --now revistag-keepalive.timer
#
# 4. Check timer status:
#      systemctl list-timers revistag-keepalive.timer
# ==============================================================================

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

# Configuration
DEFAULT_URL="https://d3y1qikecd1br.cloudfront.net/"
CONNECT_TIMEOUT=5
MAX_TIME=15

# Determine target URL: CLI argument, environment variable, or default
TARGET_URL="${PING_URL:-$DEFAULT_URL}"
VERBOSE=false

for arg in "$@"; do
    case "$arg" in
        -v|--verbose)
            VERBOSE=true
            ;;
        -h|--help)
            echo "Usage: $0 [URL] [-v|--verbose] [-h|--help]"
            echo "Pings the specified endpoint (default: ${DEFAULT_URL}) to keep connections alive."
            exit 0
            ;;
        http*://*)
            TARGET_URL="$arg"
            ;;
    esac
done

if [[ "$VERBOSE" == "true" ]]; then
    TIMESTAMP="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
    METRICS=$(curl -s -S -o /dev/null \
        --connect-timeout "$CONNECT_TIMEOUT" \
        --max-time "$MAX_TIME" \
        -w "HTTP %{http_code} | DNS: %{time_namelookup}s | Connect: %{time_connect}s | TLS: %{time_appconnect}s | Total: %{time_total}s" \
        "$TARGET_URL" 2>&1)
    echo "[$TIMESTAMP] Ping $TARGET_URL -> $METRICS"
else
    # Silent ping for cron execution
    curl -s -o /dev/null \
        --connect-timeout "$CONNECT_TIMEOUT" \
        --max-time "$MAX_TIME" \
        "$TARGET_URL"
fi
