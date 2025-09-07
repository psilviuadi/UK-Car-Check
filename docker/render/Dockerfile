# ---- Build Frontend (Vite) ----
FROM node:20 AS build-frontend
WORKDIR /app

# Copy package files and install fresh
COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

# Copy app and build
COPY . .
RUN npm run build

# ---- Backend (PHP + Nginx) ----
FROM php:8.2-fpm AS php-backend
WORKDIR /var/www/html

# Install system deps + PHP extensions + supervisor + nginx
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zip supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel app
COPY . .

# Install PHP deps optimized
RUN composer install --no-dev --optimize-autoloader

# Copy built assets
COPY --from=build-frontend /app/public/build ./public/build

# Remove default Nginx site
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf

# Copy configs
COPY docker/render/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

# Make storage/cache writable
RUN chmod -R 777 storage bootstrap/cache

# Force Laravel debug + stderr logging
ENV APP_ENV=local
ENV APP_DEBUG=true
ENV LOG_CHANNEL=stderr

# Send PHP-FPM logs to container stdout/stderr
RUN sed -i "s|;error_log = .*|error_log = /proc/self/fd/2|" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s|;access.log = .*|access.log = /proc/self/fd/1|" /usr/local/etc/php-fpm.d/www.conf

EXPOSE 80

# Start supervisord (runs php-fpm + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
