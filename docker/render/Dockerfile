# ---- Build Vue assets ----
FROM node:20 AS build-frontend
WORKDIR /app

# Copy package files and install fresh
COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

# Copy all files and build
COPY . .
RUN npm run build

# ---- PHP/Laravel ----
FROM php:8.2-fpm AS php-backend
WORKDIR /var/www/html

# Install system dependencies + php extensions + supervisor + nginx
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zip supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Vue build into Laravel public
COPY --from=build-frontend /app/dist ./public/build

# Remove default nginx config
RUN rm -f /etc/nginx/conf.d/default.conf

# Copy our nginx config
COPY docker/render/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

# Set PHP-FPM error log to stderr
RUN sed -i "s|;error_log = log/php-fpm.log|error_log = /proc/self/fd/2|" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s|;access.log = log/$pool.access.log|access.log = /proc/self/fd/1|" /usr/local/etc/php-fpm.d/www.conf


# Expose port
EXPOSE 80

# Start supervisord (runs php-fpm + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
