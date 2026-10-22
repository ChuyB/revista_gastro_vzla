FROM php:5.6-apache

# 1. Redirigir repositorios a archive.debian.org e ignorar verificación de expiración de claves GPG
RUN sed -i s/deb.debian.org/archive.debian.org/g /etc/apt/sources.list \
    && sed -i s/security.debian.org/archive.debian.org/g /etc/apt/sources.list \
    && sed -i '/stretch-updates/d' /etc/apt/sources.list \
    && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until

# 2. Instalar librerías del sistema permitiendo paquetes no autenticados (claves GPG caducadas)
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# 3. Configurar e instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install mysql mysqli pdo pdo_mysql gd mbstring zip xml

# 4. Habilitar módulo mod_rewrite de Apache
RUN a2enmod rewrite

# 5. Entrypoint para asegurar permisos en los volúmenes montados
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
