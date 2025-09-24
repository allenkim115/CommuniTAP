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
composer run dev
```

App: http://127.0.0.1:8000 (starts Laravel, Vite, and the queue worker).

### Troubleshoot

- Missing APP_KEY → `php artisan key:generate`
- Unstyled pages → keep dev script running (Vite)
- Files not loading → `php artisan storage:link`

Note: First registered user becomes admin automatically.
