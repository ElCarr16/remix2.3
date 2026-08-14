# Remix Official Store

<p align="center">
  <strong>Laravel 12 + Bagisto 2.4.x e-commerce platform</strong>
</p>

---

## Overview

This repository contains a Bagisto-based e-commerce application. It uses the modular architecture of Bagisto with Laravel as the core framework, plus Vite and Tailwind CSS for the frontend assets.

### Key features
- Modular package-based architecture
- Admin and storefront modules
- Product/catalog management
- Customer and order workflows
- Multi-locale and multi-currency support
- Payment and shipping integrations
- API and theme customization support

---

## Technology stack

| Layer | Technology | Notes |
|------|------------|-------|
| Backend | Laravel 11. | Main application framework |
| E-commerce | Bagisto 2.3.19 | Core commerce modules |
| PHP | PHP 8.3+ | Required runtime |
| Frontend | Vue.js 3, Blade, Tailwind CSS | Admin/storefront UI and styling |
| Asset build | Vite | Frontend bundling and HMR |
| Database | MySQL 8.0+ | Primary database |
| Cache/queue | Redis (optional) | Recommended for production-like environments |
| Search | Elasticsearch (optional) | Used by some catalog/search features |
| Testing | Pest, PHPUnit | Automated test suite |

---

## Prerequisites

Before installing, make sure the following tools are available:

- PHP 8.3 or 8.4
- Composer 2.x
- Node.js 18+ (recommended: 20 LTS)
- npm
- MySQL 8.0+
- Git
- Extension requirements for PHP:
  - ext-curl
  - ext-intl
  - ext-mbstring
  - ext-openssl
  - ext-pdo
  - ext-pdo_mysql
  - ext-tokenizer
  - ext-calendar

For a more complete local setup, Redis and Elasticsearch are also recommended.

---

## Installation guide

### 1. Clone the repository
```bash
git clone https://github.com/ElCarr16/remix-clothing-store-website.git
cd remix-clothing-store-website
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Configure environment
```bash
cp .env.example .env
```

Update the environment values for your local database and app URL:
```env
APP_NAME="Bagisto"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bagisto
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate application key
```bash
php artisan key:generate
```

### 5. Install Bagisto
```bash
php artisan bagisto:install
```

This command will prepare the database, run migrations, seed data, and publish the required assets.

### 6. Create storage link
```bash
php artisan storage:link
```

### 7. Clear caches
```bash
php artisan optimize:clear
```

### 8. Build frontend assets
If you want to build the bundled frontend assets for the admin and shop packages, run:
```bash
cd packages/Webkul/Admin && npm install && npm run build
cd ../Shop && npm install && npm run build
```

### 9. Start the development server
```bash
php artisan serve
```

Open the browser at:
```text
http://localhost:8000
```

---

## Useful development commands

### Run tests
```bash
php artisan test --compact
```

### Check code style
```bash
vendor/bin/pint --test
```

### Fix code style
```bash
vendor/bin/pint
```

### Check translations
```bash
php artisan bagisto:translations:check
```

---

## Project structure

```text
app/                  # Application shell
bootstrap/           # Framework bootstrapping
config/              # Configuration files
database/            # Migrations and seeders
packages/Webkul/     # Core Bagisto packages
public/              # Public web root
resources/           # Views, assets, and themes
routes/              # Route definitions
tests/               # Automated tests
```

---

## Contributing

Contributions are welcome. Please follow the standard Git workflow:

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Open a pull request

---

## Documentation and support

- Bagisto documentation: https://devdocs.bagisto.com
- Bagisto forums: https://forums.bagisto.com
- GitHub issues: https://github.com/bagisto/bagisto/issues

---

## License

This project is distributed under the MIT license.
