# ABN Recruitment Portal — Setup Guide

## Prerequisites

- Docker & Docker Compose
- PHP 8.3+
- Composer

## Setup

```bash
# 1. Clone and enter the Laravel app
cd abn-recruitment-laravel

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Start Docker containers (PostgreSQL, Redis, Nginx, App)
docker compose up -d

# 5. Generate app key
docker exec abn-app php artisan key:generate

# 6. Run migrations and seeders (creates tables + demo data)
docker exec abn-app php artisan migrate:fresh --seed --force

# 7. Storage link for file uploads
docker exec abn-app php artisan storage:link
```

## Access

| URL | Description |
|-----|-------------|
| `http://localhost:8082` | Application |
| `http://localhost:8080` | pgAdmin (email: `admin@admin.com`, pass: `admin`) |

## Demo Login

All demo users use password: `password`

| User | Email | Role |
|------|-------|------|
| Rajesh Kumar | `rajesh.kumar@abncorporation.com` | HR Manager (full access) |
| Sarah Wilson | `sarah.wilson@abncorporation.com` | Recruiter (limited access) |

## Quick Commands

| Command | Description |
|---------|-------------|
| `docker compose up -d` | Start all containers |
| `docker compose down` | Stop all containers |
| `docker exec abn-app php artisan migrate:fresh --seed` | Reset DB with demo data |
| `docker exec abn-app php artisan tinker` | Laravel REPL |
| `docker exec abn-pgsql psql -U abn_user -d abn_recruitment` | Direct DB access |

## Committing Changes

```bash
cd abn-recruitment-laravel
git add -A
git commit -m "feat: description"
git push origin main
```
