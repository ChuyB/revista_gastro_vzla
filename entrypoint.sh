#!/bin/bash
set -e

# 1. Configuración de nombres de host permitidos y verificación de CloudFront
export ALLOWED_HOSTS="${ALLOWED_HOSTS:-}"
export REQUIRE_CLOUDFRONT="${REQUIRE_CLOUDFRONT:-false}"
export BLOCK_RAW_IPS="${BLOCK_RAW_IPS:-false}"

# Limpiar comillas iniciales y finales si están presentes en ALLOWED_HOSTS
ALLOWED_HOSTS="${ALLOWED_HOSTS#\"}"
ALLOWED_HOSTS="${ALLOWED_HOSTS%\"}"
ALLOWED_HOSTS="${ALLOWED_HOSTS#\'}"
ALLOWED_HOSTS="${ALLOWED_HOSTS%\'}"
export ALLOWED_HOSTS

# Validación de seguridad: ALLOWED_HOSTS debe contener caracteres válidos para dominios, comodines, IPv6 e IPs
if [[ -n "${ALLOWED_HOSTS}" && ! "${ALLOWED_HOSTS}" =~ ^[][A-Za-z0-9_.*,:[:space:]-]+$ ]]; then
    echo "ERROR: ALLOWED_HOSTS contains invalid characters. Only alphanumeric characters, dots, hyphens, wildcards (*), colons, brackets ([]), and commas are allowed." >&2
    exit 1
fi

# Normalizar REQUIRE_CLOUDFRONT a true o false
if [[ "${REQUIRE_CLOUDFRONT}" =~ ^([Tt][Rr][Uu][Ee]|1|[Yy][Ee][Ss])$ ]]; then
    REQUIRE_CLOUDFRONT="true"
else
    REQUIRE_CLOUDFRONT="false"
fi
export REQUIRE_CLOUDFRONT

# Normalizar BLOCK_RAW_IPS a true o false
if [[ "${BLOCK_RAW_IPS}" =~ ^([Tt][Rr][Uu][Ee]|1|[Yy][Ee][Ss])$ ]]; then
    BLOCK_RAW_IPS="true"
else
    BLOCK_RAW_IPS="false"
fi
export BLOCK_RAW_IPS

# Construir expresión regular ALLOWED_HOSTS_PATTERN a partir de ALLOWED_HOSTS
ALLOWED_HOSTS_PATTERN=""
if [[ -n "${ALLOWED_HOSTS}" ]]; then
    IFS=',' read -ra HOST_ITEMS <<< "${ALLOWED_HOSTS}"
    PATTERNS=()
    for item in "${HOST_ITEMS[@]}"; do
        # Eliminar espacios en blanco al inicio y al final
        item="$(echo "${item}" | sed -e 's/^[[:space:]]*//' -e 's/[[:space:]]*$//')"
        [ -z "${item}" ] && continue
        if [[ "${item}" == \*.* ]]; then
            base="${item#\*.}"
            base_escaped="${base//./\\.}"
            PATTERNS+=("([a-zA-Z0-9-]+\\.)*${base_escaped}")
        else
            escaped="${item//./\\.}"
            escaped="${escaped//\[/\\[}"
            escaped="${escaped//\]/\\]}"
            PATTERNS+=("${escaped}")
        fi
    done
    if [ ${#PATTERNS[@]} -gt 0 ]; then
        ALLOWED_HOSTS_PATTERN=$(IFS='|'; echo "${PATTERNS[*]}")
    fi
fi
export ALLOWED_HOSTS_PATTERN

# Asegurar que las variables estén en /etc/apache2/envvars para Apache y sus procesos hijos
if [ -f /etc/apache2/envvars ]; then
    sed -i '/export CLOUDFRONT_/d' /etc/apache2/envvars
    sed -i '/export ALLOWED_HOSTS/d' /etc/apache2/envvars
    sed -i '/export REQUIRE_CLOUDFRONT/d' /etc/apache2/envvars
    sed -i '/export BLOCK_RAW_IPS/d' /etc/apache2/envvars
    echo "export ALLOWED_HOSTS=\"${ALLOWED_HOSTS}\"" >> /etc/apache2/envvars
    echo "export ALLOWED_HOSTS_PATTERN=\"${ALLOWED_HOSTS_PATTERN}\"" >> /etc/apache2/envvars
    echo "export REQUIRE_CLOUDFRONT=\"${REQUIRE_CLOUDFRONT}\"" >> /etc/apache2/envvars
    echo "export BLOCK_RAW_IPS=\"${BLOCK_RAW_IPS}\"" >> /etc/apache2/envvars
fi

# 2. Asegurar directorios críticos dentro de ojs_code y ojs_files
mkdir -p /var/www/html/cache/t_compile \
         /var/www/html/cache/t_cache \
         /var/www/html/public \
         /var/ojs/files

# Ajustar permisos dinámicamente en cada inicio del contenedor
chown -R www-data:www-data /var/www/html/cache /var/www/html/public /var/ojs/files
chmod -R 775 /var/www/html/cache /var/www/html/public /var/ojs/files

# Arrancar Apache en primer plano
exec apache2-foreground
