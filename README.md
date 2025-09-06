# UK Car Checker (Laravel + Vue + Docker)

This project runs a Laravel backend with a Vue (Vite) frontend, fully containerized using Docker and Docker Compose.
You can run it in development mode (with hot reload) or production mode (with built assets).

## Requirements

- Docker Desktop (Windows/macOS) or Docker Engine (Linux)
- Docker Compose v2+

## Setup

1. Clone this repository:

    git clone https://github.com/yourname/uk-car-checker.git
    cd uk-car-checker

2. Copy `.env.example` to `.env`:

    copy .env.example .env

   Update the following values in `.env`:

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=laravel
    DB_PASSWORD=secret

3. Build and start containers:

    docker-compose up --build

## Running the App

### Development Mode (hot reload)

- Laravel + Nginx: http://localhost:8080
- Vite dev server: http://localhost:5173

Changes to Vue or Laravel files will hot reload automatically.

### Production Mode (built assets)

1. Build frontend assets:

    docker-compose run --rm node-build

   This generates static files in `public/build`.

2. Stop the dev container (`node-dev`) if running:

    docker-compose stop node-dev

3. Restart Laravel + Nginx:

    docker-compose up laravel nginx db

Visit http://localhost:8080 — now Nginx serves the built Vue files.

## Database

Default MySQL credentials:

    host: db
    port: 3306
    database: laravel
    username: laravel
    password: secret

Run migrations inside the Laravel container:

    docker-compose exec laravel php artisan migrate

## Useful Commands

- Rebuild all containers:

    docker-compose build --no-cache

- Start containers in background:

    docker-compose up -d

- Stop containers:

    docker-compose down

- Access Laravel container:

    docker-compose exec laravel bash

## Troubleshooting

- Vite manifest not found → Run dev server (`node-dev`) or build assets (`node-build`).
- Node version error → Make sure `Dockerfile.node` uses Node 20+ or 22+.
- Rollup optional dependency errors → Remove `node_modules` and `package-lock.json` and rebuild.

Linux/macOS:

    rm -rf node_modules package-lock.json
    docker-compose build --no-cache node-dev node-build
    docker-compose run --rm node-dev npm install

Windows PowerShell:

    Remove-Item -Recurse -Force node_modules, package-lock.json
    docker-compose build --no-cache node-dev node-build
    docker-compose run --rm node-dev npm install

## Stack

- Backend: Laravel (PHP 8.2)
- Frontend: Vue 3 + Vite
- Database: MySQL 8
- Web Server: Nginx
- Containerization: Docker + Docker Compose

Happy hacking!

