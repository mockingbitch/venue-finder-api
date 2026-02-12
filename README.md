# VenueFinder API (Laravel)

API REST cho VenueFinder: venues, map, favorites, quote requests.

---

## Mục lục

- [Docker (compose riêng + network)](#docker-compose-riêng--network)
- [Cài đặt (chạy tay)](#cài-đặt-chạy-tay)
- [Biến môi trường](#biến-môi-trường)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [API Endpoints](#api-endpoints)
- [Database & Migrations](#database--migrations)

---

## Docker (compose riêng + network)

Backend dùng network **`venuefinder_network`**. Tạo trước từ thư mục gốc: `make network` hoặc `docker network create venuefinder_network`.

```bash
# Từ thư mục backend
docker compose up -d --build
```

- **API:** http://localhost:8000/api  
- **Seed:** `docker compose exec backend php artisan db:seed`

---

## Cài đặt (chạy tay)

**Yêu cầu:** PHP 8.2+, Composer, MySQL 8.x (hoặc MariaDB).

1. **Cài đặt dependencies:**

```bash
composer install
```

2. **Cấu hình môi trường:**

```bash
cp .env.example .env
php artisan key:generate
```

3. **Cấu hình database trong `.env`** (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

4. **Chạy migrations:**

```bash
php artisan migrate
```

5. **(Tùy chọn) Seed dữ liệu mẫu:**

```bash
php artisan db:seed
```

6. **Chạy server:**

```bash
php artisan serve
```

→ API: **http://localhost:8000/api**

---

## Biến môi trường

| Biến | Mô tả | Mặc định |
|------|--------|----------|
| `APP_NAME` | Tên ứng dụng | VenueFinder |
| `APP_ENV` | Môi trường (local, production, ...) | local |
| `APP_DEBUG` | Bật debug | true |
| `APP_KEY` | Key mã hóa (tạo bằng `php artisan key:generate`) | — |
| `APP_URL` | URL backend | http://localhost:8000 |
| `FRONTEND_URL` | URL frontend (dùng cho CORS) | http://localhost:3000 |
| `DB_CONNECTION` | Loại DB | mysql |
| `DB_HOST` | Host MySQL | 127.0.0.1 |
| `DB_PORT` | Port MySQL | 3306 |
| `DB_DATABASE` | Tên database | venuefinder |
| `DB_USERNAME` | User MySQL | root |
| `DB_PASSWORD` | Mật khẩu MySQL | — |

---

## Cấu trúc thư mục

```
backend/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── VenueController.php    # venues, map, show
│   │   ├── FavoriteController.php # favorites, toggle
│   │   └── QuoteRequestController.php
│   └── Models/
│       ├── Venue.php
│       ├── Space.php
│       ├── Favorite.php
│       ├── QuoteRequest.php
│       └── User.php
├── config/
│   ├── app.php
│   ├── cors.php
│   └── database.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/api.php
├── Dockerfile
├── docker-compose.yml
└── docker-entrypoint.sh
```

---

## API Endpoints

Base URL: **http://localhost:8000/api**

### GET /venues

Danh sách venues (có phân trang và filter).

**Query params:**

| Tham số | Kiểu | Mô tả |
|---------|------|--------|
| `page` | int | Trang (mặc định 1) |
| `per_page` | int | Số item/trang (tối đa 50) |
| `search` | string | Tìm trong name, description, suburb |
| `category` | string | Lọc theo category |
| `suburb` | string | Lọc theo suburb (like) |
| `min_capacity` | int | Capacity >= giá trị |
| `max_price_level` | int | Price level <= giá trị (1–5) |

**Response:** `{ "data": [...], "meta": { "current_page", "last_page", "per_page", "total", "venues_count", "spaces_count" } }`

---

### GET /venues/map

Venues cho bản đồ (có lat/lng).

**Query params:** `category`, `suburb`, `bounds` (chuỗi lat,lng,lat,lng).

**Response:** `{ "data": [ { "id", "name", "slug", "lat", "lng", "category", "suburb", "rating", "price_level" }, ... ], "meta": { "venues_count", "spaces_count" } }`

---

### GET /venues/{slug}

Chi tiết venue theo slug.

**Response:** `{ "data": { Venue + relations "spaces" } }`

---

### GET /favorites

Danh sách ID venue đã favorite.

**Headers:** `X-Session-Id` (bắt buộc cho guest; nếu có auth thì dùng user).

**Response:** `{ "data": [ 1, 2, 3 ] }`

---

### POST /favorites/{venue_id}/toggle

Bật/tắt favorite cho venue.

**Headers:** `X-Session-Id` (cho guest).

**Response:** `{ "data": { "is_favorited": true|false } }`

---

### POST /quote-requests

Gửi yêu cầu báo giá.

**Body (JSON):**

| Trường | Kiểu | Bắt buộc | Mô tả |
|--------|------|----------|--------|
| `venue_id` | int | Có | ID venue |
| `name` | string | Có | Họ tên |
| `email` | string | Có | Email |
| `phone` | string | Không | Số điện thoại |
| `event_date` | string (date) | Không | Ngày sự kiện |
| `guests` | int | Không | Số khách |
| `message` | string | Không | Tin nhắn |

**Response (201):** `{ "data": { ... }, "message": "Quote request submitted." }`

**Lỗi (422):** `{ "errors": { "field": ["..."] } }`

---

## Database & Migrations

**Bảng chính:**

- **venues** – name, slug, category, suburb, lat, lng, capacity, area_sqm, rating, reviews_count, price_level, image_url, has_offer, ...
- **spaces** – venue_id, name, capacity, area_sqm, ...
- **favorites** – user_id (nullable), venue_id, session_id (nullable)
- **quote_requests** – venue_id, user_id (nullable), name, email, phone, event_date, guests, message, status
- **users** – Laravel auth (name, email, password)

**Lệnh:**

```bash
php artisan migrate          # Chạy migrations
php artisan migrate:fresh    # Drop all + migrate (cẩn thận)
php artisan db:seed         # Seed dữ liệu mẫu
```
