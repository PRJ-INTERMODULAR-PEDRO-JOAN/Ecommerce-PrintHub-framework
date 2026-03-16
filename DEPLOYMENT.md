# PrintHub — Deployment Guide

This document covers the complete deployment process for **PrintHub** (a Laravel 12 e-commerce application).  
It includes local development setup, production Docker deployment, CI/CD pipelines, and AWS infrastructure.

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Local Development](#local-development)
3. [Production Deployment (Docker + VPS)](#production-deployment-docker--vps)
4. [HTTPS with Let's Encrypt](#https-with-lets-encrypt)
5. [AWS Deployment (ECS Fargate)](#aws-deployment-ecs-fargate)
6. [CI/CD Pipelines (GitHub Actions)](#cicd-pipelines-github-actions)
7. [Environment Variables Reference](#environment-variables-reference)
8. [DNS Configuration](#dns-configuration)

---

## Architecture Overview

```
                        ┌────────────────────────────────────────────┐
                        │              AWS / VPS                     │
                        │                                            │
 Client ──HTTPS──►  [ Nginx (443) ] ──────► [ PHP-FPM (9000) ]      │
                   [  + Certbot   ]         [ Laravel App     ]      │
                        │                        │                   │
                        │                   [ MySQL 8.4 ]            │
                        │                   [ Redis 7   ]            │
                        │                   [ Queue Worker ]         │
                        │                   [ Scheduler   ]          │
                        │                                            │
                        │  Static files (S3 / public/build)          │
                        └────────────────────────────────────────────┘
```

**Service separation:**

| Service    | Role                                      | Port  |
|------------|-------------------------------------------|-------|
| `nginx`    | TLS termination, static file serving, reverse proxy | 80 / 443 |
| `app`      | PHP-FPM — Laravel backend                 | 9000  |
| `mysql`    | Relational database                       | 3306  |
| `redis`    | Cache, session store, queue backend       | 6379  |
| `queue`    | Async job processing (`queue:work`)       | —     |
| `scheduler`| Laravel cron replacement (`schedule:run`) | —     |
| `certbot`  | Let's Encrypt certificate issuance & renewal | —  |

---

## Local Development

### Prerequisites

- PHP 8.2+, Composer 2, Node.js 22, npm
- Docker Desktop (optional — for Laravel Sail)

### Quick Start (without Docker)

```bash
cd Ecommerce-PrintHub-framework
composer run setup      # installs deps, generates key, runs migrations, builds assets
composer run dev        # starts Laravel, queue, logs and Vite concurrently
```

### Quick Start (Laravel Sail)

```bash
cd Ecommerce-PrintHub-framework
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

The application will be available at `http://localhost`.

---

## Production Deployment (Docker + VPS)

### Requirements

- A Linux server (Ubuntu 22.04 LTS recommended)
- Docker Engine 25+ and Docker Compose v2
- A domain name with DNS A record pointing to the server's public IP

### Steps

1. **Clone the repository on the server:**

   ```bash
   git clone https://github.com/PRJ-INTERMODULAR-PEDRO-JOAN/Ecommerce-PrintHub-framework.git
   cd Ecommerce-PrintHub-framework/Ecommerce-PrintHub-framework
   ```

2. **Configure environment:**

   ```bash
   cp .env.example .env
   # Edit .env and fill in every variable (see Environment Variables Reference below)
   nano .env
   ```

   At minimum set:
   - `APP_KEY` — generate with `php artisan key:generate --show`
   - `APP_DOMAIN` — your fully-qualified domain name (e.g. `printhub.example.com`)
   - `CERTBOT_EMAIL` — email for Let's Encrypt notifications
   - `DB_*`, `DB_ROOT_PASSWORD` — database credentials
   - `REDIS_PASSWORD` — Redis password

3. **Start services:**

   ```bash
   docker compose -f docker-compose.prod.yml up -d
   ```

4. **Run database migrations:**

   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

5. **Clear caches:**

   ```bash
   docker compose -f docker-compose.prod.yml exec app php artisan config:cache
   docker compose -f docker-compose.prod.yml exec app php artisan route:cache
   docker compose -f docker-compose.prod.yml exec app php artisan view:cache
   ```

---

## HTTPS with Let's Encrypt

### First-time certificate issuance

After starting services (so Certbot's webroot is served by Nginx on port 80):

```bash
docker compose -f docker-compose.prod.yml run --rm certbot certonly \
  --webroot -w /var/www/certbot \
  -d "${APP_DOMAIN}" -d "www.${APP_DOMAIN}" \
  --email "${CERTBOT_EMAIL}" \
  --agree-tos \
  --no-eff-email
```

Then reload Nginx to pick up the new certificates:

```bash
docker compose -f docker-compose.prod.yml exec nginx nginx -s reload
```

### Automatic renewal

The `certbot` service in `docker-compose.prod.yml` runs `certbot renew` every 12 hours automatically.  
Nginx reloads are triggered by the `certbot` container's entrypoint after each successful renewal.

### Self-signed certificate (staging / testing)

For non-production environments, generate a self-signed certificate:

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/privkey.pem \
  -out docker/nginx/ssl/fullchain.pem \
  -subj "/CN=localhost"
```

---

## AWS Deployment (ECS Fargate)

### Overview

| AWS Service        | Purpose                               |
|--------------------|---------------------------------------|
| **ECR**            | Container image registry              |
| **ECS Fargate**    | Serverless container orchestration    |
| **RDS MySQL 8.4**  | Managed relational database           |
| **ElastiCache Redis** | Managed Redis for cache/queues     |
| **S3**             | File storage (`FILESYSTEM_DISK=s3`)   |
| **ALB**            | Application Load Balancer (HTTPS)     |
| **ACM**            | TLS certificate (AWS-managed)         |
| **Route 53**       | DNS management                        |
| **SSM Parameter Store** | Non-sensitive configuration      |
| **Secrets Manager**| Sensitive values (passwords, keys)    |
| **CloudWatch Logs**| Centralised container logging         |

### One-time provisioning

```bash
export AWS_REGION=eu-west-1
export APP_DOMAIN=printhub.example.com
bash infrastructure/aws/provision-aws.sh
```

The script creates: ECR repository, ECS cluster, IAM roles, security groups, S3 bucket, CloudWatch log groups, and SSM parameters.

### Registering ECS task definitions

After filling in `ACCOUNT_ID` and `REGION` placeholders in the JSON files:

```bash
# Replace placeholder values first
sed -i "s/ACCOUNT_ID/$ACCOUNT_ID/g; s/REGION/$AWS_REGION/g" \
  infrastructure/aws/ecs-task-backend.json \
  infrastructure/aws/ecs-task-queue.json

aws ecs register-task-definition \
  --cli-input-json file://infrastructure/aws/ecs-task-backend.json

aws ecs register-task-definition \
  --cli-input-json file://infrastructure/aws/ecs-task-queue.json
```

### Storing secrets

```bash
# Application key (generate first: php artisan key:generate --show)
aws secretsmanager create-secret \
  --name "printhub/app-key" \
  --secret-string "base64:YOUR_APP_KEY_HERE"

# Database password
aws secretsmanager create-secret \
  --name "printhub/db-password" \
  --secret-string "YOUR_DB_PASSWORD"

# Redis password
aws secretsmanager create-secret \
  --name "printhub/redis-password" \
  --secret-string "YOUR_REDIS_PASSWORD"
```

---

## CI/CD Pipelines (GitHub Actions)

### Workflows

| File | Trigger | Purpose |
|------|---------|---------|
| `.github/workflows/ci.yml` | Push / PR to `main`, `develop` | Run PHPUnit tests (PHP 8.2 + 8.3), Pint lint, Node build |
| `.github/workflows/cd.yml` | Push to `main`, manual dispatch | Build & push Docker image to ECR, run migrations, deploy to ECS |

### Required GitHub Secrets

Configure these in **Settings → Secrets and variables → Actions**:

| Secret | Description |
|--------|-------------|
| `AWS_ACCESS_KEY_ID` | IAM user access key with ECR/ECS permissions |
| `AWS_SECRET_ACCESS_KEY` | IAM user secret key |
| `ECS_SUBNET_ID` | VPC subnet ID for ECS tasks |
| `ECS_SECURITY_GROUP_ID` | Security group ID for ECS tasks |

### Required GitHub Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `AWS_REGION` | `eu-west-1` | AWS region |

---

## Environment Variables Reference

| Variable | Required | Description |
|----------|----------|-------------|
| `APP_NAME` | ✓ | Application name |
| `APP_ENV` | ✓ | `local` / `production` |
| `APP_KEY` | ✓ | Laravel encryption key |
| `APP_DEBUG` | ✓ | `false` in production |
| `APP_URL` | ✓ | Full application URL |
| `APP_DOMAIN` | prod | Domain name for Nginx/Certbot |
| `CERTBOT_EMAIL` | prod | Email for Let's Encrypt |
| `DB_CONNECTION` | ✓ | `mysql` in production |
| `DB_HOST` | prod | Database host |
| `DB_DATABASE` | prod | Database name |
| `DB_USERNAME` | prod | Database user |
| `DB_PASSWORD` | prod | Database password |
| `DB_ROOT_PASSWORD` | prod | MySQL root password (Docker) |
| `REDIS_HOST` | prod | Redis host |
| `REDIS_PASSWORD` | prod | Redis password |
| `REDIS_PORT` | — | Default: `6379` |
| `CACHE_STORE` | prod | `redis` in production |
| `SESSION_DRIVER` | prod | `redis` in production |
| `QUEUE_CONNECTION` | prod | `redis` in production |
| `FILESYSTEM_DISK` | prod | `s3` in production |
| `AWS_ACCESS_KEY_ID` | prod | AWS access key (S3) |
| `AWS_SECRET_ACCESS_KEY` | prod | AWS secret key (S3) |
| `AWS_DEFAULT_REGION` | prod | AWS region |
| `AWS_BUCKET` | prod | S3 bucket name |
| `MAIL_MAILER` | prod | SMTP mailer |
| `MAIL_HOST` | prod | SMTP host |
| `MAIL_PORT` | prod | SMTP port |
| `MAIL_USERNAME` | prod | SMTP user |
| `MAIL_PASSWORD` | prod | SMTP password |
| `MAIL_FROM_ADDRESS` | prod | Sender address |
| `REGISTRY_IMAGE` | CI/CD | Docker image name |
| `IMAGE_TAG` | CI/CD | Docker image tag |

---

## DNS Configuration

### VPS / Single Server

Create an **A record** pointing your domain to the server's public IP:

```
printhub.example.com.     300  IN  A    203.0.113.10
www.printhub.example.com. 300  IN  CNAME printhub.example.com.
```

### AWS (Route 53 + ALB)

1. Create a **Hosted Zone** in Route 53 for your domain.
2. Update the nameservers at your domain registrar to the Route 53 NS records.
3. Create an **A record (Alias)** pointing `printhub.example.com` to the ALB DNS name.
4. Request an ACM certificate for `printhub.example.com` (and `www.*`) — DNS validation.
5. Attach the ACM certificate to the ALB HTTPS listener.

```
printhub.example.com.     A  ALIAS  printhub-alb-1234.eu-west-1.elb.amazonaws.com
```
