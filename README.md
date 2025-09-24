## Communitap — Laravel 12 + Vite

A Laravel application with Vite, Tailwind, Alpine. This guide helps anyone who clones the repo get it running quickly (Windows-friendly, but works on macOS/Linux too).

### Prerequisites

- **PHP 8.2+** with extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, Fileinfo
- **Composer 2.x**
- **Node.js 18+** and **npm 9+**
- **SQLite** (recommended for local) or **MySQL**
- Optional: **Git**, **XAMPP** on Windows

### 1) Clone and install dependencies

```bash
git clone https://github.com/allenkim115/CommuniTAP.git
cd communitap
composer install
npm install
```

### 2) Environment setup

Create and configure `.env`:

```bash
copy .env.example .env   # Windows (PowerShell: cp .env.example .env)
php artisan key:generate
```

Set basics in `.env`:

```env
APP_NAME="Communitap"
APP_URL=http://localhost:8000
QUEUE_CONNECTION=database
```

### 3) Database options

Pick ONE: SQLite (simple) or MySQL.

- SQLite (recommended for quick start):

```bash
type NUL > database/database.sqlite  # Windows
# macOS/Linux: touch database/database.sqlite
```

Then set in `.env`:

```env
DB_CONNECTION=sqlite
```

- MySQL (if you prefer):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=communitap
DB_USERNAME=root
DB_PASSWORD= # your password
```

Run migrations (and create storage symlink):

```bash
php artisan migrate
php artisan storage:link
```

Notes:
- Jobs tables are included; `QUEUE_CONNECTION=database` will work out of the box.
- Seeder does not create an admin user. The first registered user becomes admin automatically.

### 4) Run the app

Option A — one command (starts server, queue listener, logs, and Vite):

```bash
composer run dev
```

Option B — run separately:

```bash
php artisan serve            # http://127.0.0.1:8000
php artisan queue:work       # in a second terminal
npm run dev                  # in a third terminal (Vite)
```

For production-like assets:

```bash
npm run build
```

### Testing

```bash
php artisan test
```

### Common issues (Windows/macOS/Linux)

- APP_KEY missing / 500 error: run `php artisan key:generate`.
- Vite assets 404 or unstyled pages: ensure `npm run dev` is running, or run `npm run build`.
- Storage files not loading: run `php artisan storage:link`.
- SQLite write permissions:
  - Ensure `database/database.sqlite`, `storage/` and `bootstrap/cache/` are writable.
  - On Windows, avoid protected folders; place the project in a user directory.
- PHP extensions missing: enable `fileinfo`, `pdo_sqlite` or `pdo_mysql` in `php.ini`.
- Queue not processing: confirm `QUEUE_CONNECTION=database` and run `php artisan queue:work`.

### Useful commands

```bash
# Clear and warm caches
php artisan optimize:clear

# Re-run migrations from scratch (DANGER: data loss)
php artisan migrate:fresh --seed

# Tail application logs (requires pail; included in dev script)
php artisan pail
```

### Tech stack

- Laravel 12, PHP 8.2
- Vite 7, Tailwind CSS, Alpine.js
- Pest for tests

### Notes for contributors

- Follow PSR-12 and run `composer test` before pushing.
- Frontend changes require `npm run dev` running for hot reload.

---

If you hit any setup issues, please open an issue with your OS, PHP/Node versions, and error logs from `storage/logs/laravel.log`.
