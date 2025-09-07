# ---- Build Frontend (Vite) ----
FROM node:20 AS build-frontend
WORKDIR /app

# Copy package files and install clean
COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

# Copy all sources and build
COPY . .
RUN npm run build

# ---- Backend (PHP + Nginx) ----
FROM php:8.2-fpm AS php-backend
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zip supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel source
COPY . .

# Install PHP dependencies (optimized, no dev)
RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy built Vue assets from build stage
COPY --from=build-frontend /app/public/build ./public/build

# Remove ALL default Nginx configs and replace with ours
RUN rm -f /etc/nginx/conf.d/*
COPY docker/render/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

# Send PHP-FPM logs to container stdout/stderr
RUN sed -i "s|;error_log = .*|error_log = /proc/self/fd/2|" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s|;access.log = .*|access.log = /proc/self/fd/1|" /usr/local/etc/php-fpm.d/www.conf

# Debug step: list Laravel public folder contents in build logs
RUN ls -al /var/www/html/public

EXPOSE 80

# Start supervisor (runs PHP-FPM + Nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
