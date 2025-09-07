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
COPY --from=build-frontend /app/public/build ./public/build

# Copy nginx + supervisor configs
COPY docker/render/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

# Expose port
EXPOSE 80

# Start supervisord (runs php-fpm + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
