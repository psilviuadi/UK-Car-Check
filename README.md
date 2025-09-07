# UK Car Checker (Laravel + Vue + Docker)

This project runs a Laravel backend with a Vue (Vite) frontend, fully containerized using Docker and Docker Compose.
You can run it in development mode (with hot reload) or production mode (with built assets).

## Requirements

- Docker Desktop (Windows/macOS) or Docker Engine (Linux)
- Docker Compose v2+

## Setup

1. Clone this repository:
2. Copy `.env.example` to `.env`:
   Update the following values in `.env`:
3. Build and start containers:

    ```
    docker compose up php vue-dev nginx mysql -d --build
    ```

## Running the App

### Development Mode (hot reload)
```
    docker compose up php vue-dev nginx mysql -d --build
```

### Production Mode (built assets)
```
    docker compose build vue-prod
    docker compose up -d php vue-prod nginx mysql
```

