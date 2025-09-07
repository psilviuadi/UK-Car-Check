# ---- Build Frontend (Vite) ----
FROM node:20 AS build-frontend
WORKDIR /app

COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

COPY . .
RUN npm run build

# ---- Backend (PHP + Nginx) ----
FROM php:8.2-fpm AS php-backend
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zip supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

COPY --from=build-frontend /app/public/build ./public/build

# 🔥 Remove ALL default nginx configs
RUN rm -f /etc/nginx/conf.d/* && rm -f /etc/nginx/sites-enabled/*

COPY docker/render/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/render/supervisord.conf /etc/supervisord.conf

RUN sed -i "s|;error_log = .*|error_log = /proc/self/fd/2|" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "s|;access.log = .*|access.log = /proc/self/fd/1|" /usr/local/etc/php-fpm.d/www.conf

RUN ls -al /var/www/html/public

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
