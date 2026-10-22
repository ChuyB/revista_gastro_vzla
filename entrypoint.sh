#!/bin/bash
set -e

# Asegurar directorios críticos dentro de ojs_code y ojs_files
mkdir -p /var/www/html/cache/t_compile \
         /var/www/html/cache/t_cache \
         /var/www/html/public \
         /var/ojs/files

# Ajustar permisos dinámicamente en cada inicio del contenedor
chown -R www-data:www-data /var/www/html/cache /var/www/html/public /var/ojs/files
chmod -R 775 /var/www/html/cache /var/www/html/public /var/ojs/files

# Arrancar Apache en primer plano
exec apache2-foreground
