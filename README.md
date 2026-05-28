# ⚙️ PrintHub - Backend API (Laravel)

Este repositorio contiene el Backend del proyecto e-commerce **PrintHub**. Proporciona una API RESTful desarrollada con Laravel 11. Este documento detalla la arquitectura, infraestructura, configuración y despliegue del sistema.

---

# 🌍 1. Arquitectura Global AWS y DNS

La plataforma utiliza una arquitectura distribuida y escalable sobre Amazon Web Services (AWS).

## Arquitectura AWS

### 1. Red (VPC)

El sistema se despliega dentro de una VPC personalizada:

- **Subredes Públicas**
    - Application Load Balancer
    - Acceso a Internet

- **Subredes Privadas de Aplicación**
    - Instancias EC2 con Laravel
    - Acceso saliente mediante NAT Gateway

- **Subredes Privadas de Datos**
    - AWS RDS
    - Sin acceso público

---

### 2. Seguridad y HTTPS

- Application Load Balancer (ALB)
- HTTPS mediante Let's Encrypt
- Comunicación cifrada extremo a extremo

---

### 3. Aplicación

- Laravel 11
- Arquitectura stateless
- Preparada para Auto Scaling

---

### 4. Base de Datos

- AWS RDS MySQL/MariaDB
- Multi-AZ
- Backups automáticos

---

### 5. Security Groups

#### ALB

- HTTP → 80
- HTTPS → 443

#### EC2 App

- Solo tráfico proveniente del ALB

#### Base de Datos

- Puerto 3306 únicamente desde la aplicación

---

> 📸 **[CAPTURA 1: ARQUITECTURA AWS]**
>
> `![Arquitectura AWS](docs/captura-aws.png)`

---

# 🌐 2. Dominio y DNS

## Dominio principal

```txt
projecteXX.ddaw.es
```

Los registros DNS (`A` o `CNAME`) apuntan al balanceador AWS.

---

> 📸 **[CAPTURA 2: DNS Y HTTPS]**
>
> `![DNS HTTPS](docs/captura-dns.png)`

---

# 🏗️ 3. Tecnologías Backend

- Laravel 11
- PHP 8.2+
- MySQL 8.x
- Laravel Sanctum
- API RESTful
- Arquitectura MVC

---

# 🐳 4. Desarrollo Local con Docker

## Requisitos

- Docker
- Docker Compose

---

## Instalación

### Clonar repositorio

```bash
git clone <repo-backend>
```

---

### Variables de entorno

```bash
cp .env.example .env
```

---

### Configuración base de datos

```env
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=printhub_db
DB_USERNAME=root
DB_PASSWORD=secret
```

---

### Levantar contenedores

```bash
docker-compose up -d --build
```

---

### Preparar Laravel

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

---

> 📸 **[CAPTURA 3: DOCKER BACKEND]**
>
> `![Docker Backend](docs/docker-backend.png)`

---

# 🚀 5. CI/CD Backend

## Fases Pipeline

### Instalación dependencias

```bash
composer install
```

---

### Testing

```bash
php artisan test
```

Si falla, el despliegue se cancela automáticamente.

---

### Deploy automático

Despliegue mediante:

- SSH
- AWS CodeDeploy
- Deployer

---

### Migraciones automáticas

```bash
php artisan migrate --force
```

---

# 👥 6. Normas de Contribución

## GitFlow

- `main` → producción
- `feature/*` → desarrollo

---

## Pull Requests

- Prohibido push directo a `main`
- Revisión obligatoria
- Validación CI/CD automática

---

# 👤 7. Usuarios de Prueba

## Administrador

```txt
admin@printhub.com
password
```

---

## Cliente

```txt
client@printhub.com
password
```
