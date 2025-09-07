FROM node:20 as builder
WORKDIR /app

COPY package*.json ./
RUN rm -rf node_modules package-lock.json && npm install

COPY . .
RUN npm run build

FROM php:8.2-fpm as laravel
WORKDIR /var/www/html
COPY . .
COPY --from=builder /app/public/build ./public/build
