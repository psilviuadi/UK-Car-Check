# ---- Build Frontend (Vite) ----
FROM node:20 AS build-frontend
WORKDIR /app

# Install dependencies
COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

# Copy sources and build
COPY . .
RUN npm run build

# ---- Backend (PHP + Nginx) ----
FROM php:8.2-fpm AS php-backend
WORKDIR /var/www/html

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zip supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel source
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy built frontend assets
COPY --from=build-frontend /app/public/build ./public/build

# Remove default Nginx config and replace with ours
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf
COPY docker/render/nginx.conf /etc/nginx/conf.d/laravel.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

# Forward PHP logs to Docker log collector
RUN sed -i "s|;error_log = .*|error_log = /proc/self/fd/2|" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s|;access.log = .*|access.log = /proc/self/fd/1|" /usr/local/etc/php-fpm.d/www.conf

EXPOSE 80

# Run Supervisor (which will also run artisan optimize)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
