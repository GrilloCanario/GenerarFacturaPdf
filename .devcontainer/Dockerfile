# .devcontainer/Dockerfile
FROM php:8.3-cli-bookworm

# 1. Instala dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    # Dependencias para GD
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    # Otras utilidades comunes
    git \
    unzip \
    wget \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    # Limpia la caché de apt para reducir el tamaño de la imagen
    && rm -rf /var/lib/apt/lists/*

# 2. Instala Composer (el gestor de dependencias para PHP)
# Copia el instalador oficial desde la imagen de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Opcional: Crea un usuario no-root para mejorar la seguridad
# Esto evita ejecutar todos los comandos como superusuario.
ARG USERNAME=vscode
ARG USERID=1000
RUN useradd -m -u ${USERID} -s /bin/bash ${USERNAME}
USER ${USERNAME}

# Establece el directorio de trabajo
WORKDIR /workspace