# PrintHub — E-commerce Framework

**PrintHub** is a Laravel 12 e-commerce web application for a print-on-demand business.  
It provides product management, user authentication, and an admin dashboard.

## Quick Start

```bash
cd Ecommerce-PrintHub-framework
composer run setup   # install deps, generate key, migrate DB, build assets
composer run dev     # start dev server + Vite + queue + logs
```

For full deployment instructions (Docker, CI/CD, AWS, HTTPS) see [DEPLOYMENT.md](../DEPLOYMENT.md).

## Tech Stack

- **Backend:** PHP 8.2 · Laravel 12
- **Frontend:** Vite · Tailwind CSS
- **Database:** MySQL 8.4 (production) · SQLite (local dev)
- **Cache / Queues:** Redis 7
- **Containerisation:** Docker · Docker Compose

## Running Tests

```bash
composer run test
```

## Code Style

```bash
./vendor/bin/pint
```

