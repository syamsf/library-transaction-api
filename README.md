# How to Run the Project

## Prerequisites
- Docker
- Docker Compose

## Setup Instructions

1. **Start the Docker containers**
   ```bash
   docker compose up -d
   ```
   This will start all services (PostgreSQL, Redis, API, and Worker).

2. **Enter the API container**
   ```bash
   docker exec -it library-transaction-api bash
   ```

3. **Generate application key**
   ```bash
   php artisan key:generate
   ```

4. **Run database migrations**
   ```bash
   php artisan migrate
   ```

5. **Seed the database**
   ```bash
   php artisan db:seed
   ```

## Access the Application

- API: http://localhost:9000
- API Documentation (Scramble): http://localhost:9000/docs/api

## Useful Commands

### Stop the containers
```bash
docker compose down
```

### View logs
```bash
docker compose logs -f
```

### View specific service logs
```bash
docker compose logs -f api
docker compose logs -f worker
```

### Restart services
```bash
docker compose restart
```

### Rebuild containers
```bash
docker compose up -d --build
``` 
