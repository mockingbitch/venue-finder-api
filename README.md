# VenueFinder API

Production-ready Laravel 12 REST API for an Event Venue Marketplace.

## Stack

- **PHP 8.4** (Docker; use 8.5 when available)
- **MySQL 8**
- **Laravel 12**
- **tymon/jwt-auth** (JWT API tokens)
- **Pest** (tests)

## Features

- **RESTful API**: Venues list/show, admin CRUD
- **Role-based access**: `admin` (CRUD venues), `user` (view only)
- **Geo filtering**: Bounding box query via `min_lat`, `max_lat`, `min_lng`, `max_lng`
- **Clean architecture**: Repository, Service, FormRequest, API Resource, Policy

## API Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/venues` | No | List venues (optional bounding box) |
| GET | `/api/venues?min_lat=&max_lat=&min_lng=&max_lng=` | No | List venues in bounding box |
| GET | `/api/venues/{id}` | No | Show venue |
| POST | `/api/login` | No | Login (email, password) → token |
| POST | `/api/register` | No | Register → token |
| POST | `/api/logout` | Bearer (JWT) | Invalidate token |
| GET | `/api/me` | Bearer (JWT) | Current user |
| POST | `/api/admin/venues` | Bearer (admin) | Create venue |
| PUT | `/api/admin/venues/{id}` | Bearer (admin) | Update venue |
| DELETE | `/api/admin/venues/{id}` | Bearer (admin) | Delete venue |

## Quick Start (Docker)

```bash
# Copy env and build
cp .env.example .env
docker compose up -d --build

# Install deps and key (if app runs on host instead of in container)
docker compose exec app composer install
docker compose exec app php artisan key:generate

# JWT secret (required for auth)
docker compose exec app php artisan jwt:secret

# Run migrations and seed (inside container or with php artisan)
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed
```

API base: **http://localhost:8089**

## Local development (no Docker)

```bash
cp .env.example .env
# Set DB_HOST=127.0.0.1, DB_DATABASE=venuefinder, etc.
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Seeded users

- **Admin**: `admin@venuefinder.test` / `password`
- **User**: `user@venuefinder.test` / `password`

## Tests (Pest)

```bash
composer test
# or
./vendor/bin/pest
```

Uses in-memory SQLite by default (see `phpunit.xml`).

## Project structure

```
app/
├── Http/
│   ├── Controllers/Api/     # VenueController, AuthController
│   │   └── Admin/           # Admin VenueController
│   ├── Requests/Admin/      # StoreVenueRequest, UpdateVenueRequest
│   └── Resources/           # VenueResource
├── Models/                  # User, Venue
├── Policies/                # VenuePolicy
├── Repositories/            # VenueRepository + Contract
├── Services/                # VenueService
└── Providers/
database/
├── factories/               # UserFactory, VenueFactory
├── migrations/
└── seeders/                # UserSeeder, VenueSeeder
docker/                     # nginx, php, mysql config
tests/Feature/              # Pest feature tests
```

## License

MIT
