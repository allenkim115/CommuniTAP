## CommuniTAP 
### Prereqs

- PHP 8.2+ (with Composer)
- Node.js 18+ (with npm)
- MySQL 8+

### 1) Clone & install

```bash
git clone https://github.com/allenkim115/CommuniTAP.git
cd communitap
composer install
npm install
```

### 2) Env & key

```bash
cp .env.example .env   # Windows PowerShell: cp .env.example .env
php artisan key:generate
```

### 3) Database (MySQL)

Create a database and set your `.env` values:

```sql
CREATE DATABASE communitap CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=communitap
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and link storage:

```bash
php artisan migrate
php artisan storage:link
```

(MySQL alternative: set DB_ vars in `.env`, then run the same two commands.)

### 4) Run

```bash
php artisan serve
npm run dev 
```

App: http://127.0.0.1:8000 (starts Laravel, Vite, and the queue worker).

### Troubleshoot

- Missing APP_KEY → `php artisan key:generate`
- Unstyled pages → keep dev script running (Vite)
- Files not loading → `php artisan storage:link`

Note: First registered user becomes admin automatically.

### Authentication & Authorization

- Web (Breeze): Session-based login, registration, password reset, and email verification remain unchanged.
- API Tokens (Sanctum): Personal access tokens are enabled for programmatic access (mobile/SPA/CLI).
- Authorization: `TaskPolicy` registered; admin-only for create/update/delete by default.
- Middleware aliases:
  - `admin`: Admin-only routes (e.g., under `prefix('admin')`).
  - `user`: Blocks admins from accessing user-only pages.

### API (Sanctum) Endpoints

Base file: `routes/api.php`

```
POST   /api/login          # body: email, password, device_name? → returns token
GET    /api/me             # header: Authorization: Bearer <token>
POST   /api/logout         # header: Authorization: Bearer <token>
```

Example usage (bash):

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -d "email=user@example.com&password=secret&device_name=postman"

curl http://127.0.0.1:8000/api/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TOKEN"

curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TOKEN"
```

### Setup Notes (for new machines)

- No extra manual step beyond the usual install; tokens table is created by standard migrations:

```bash
php artisan migrate
```

- If using SPA/cookie-based Sanctum, configure `.env` for stateful domains (not required for Bearer tokens used above):

```
APP_URL=http://127.0.0.1:8000
SESSION_DOMAIN=127.0.0.1
SANCTUM_STATEFUL_DOMAINS=127.0.0.1:5173
```

### Windows Composer Tip

If archive writes fail during `composer install` on Windows, prefer source installs:

```bash
composer install --prefer-source
```
