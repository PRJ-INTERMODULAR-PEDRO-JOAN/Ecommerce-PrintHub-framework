# ⚙️ PrintHub - Backend API (Laravel)

# 📖 Descripción General

Este repositorio contiene el backend del proyecto e-commerce **PrintHub**, desplegado bajo el dominio oficial:

```txt
api.projecte01.ddaw.es
```

La aplicación proporciona una API RESTful desarrollada con Laravel 11 y preparada para ejecutarse en entornos cloud escalables utilizando AWS, Docker y CI/CD automatizado.

El sistema gestiona:

- Usuarios
- Autenticación
- Productos
- Pedidos
- Carrito de compra
- Administración

---

# 🌐 1. DNS DEL PROYECTO

## Dominio principal

```txt
projecte01.ddaw.es
```

---

## Subdominios configurados

| Servicio    | Dominio                |
| ----------- | ---------------------- |
| Frontend    | projecte01.ddaw.es     |
| Backend API | api.projecte01.ddaw.es |

---

## Configuración DNS

Se utiliza una zona DNS delegada donde se han configurado:

| Tipo  | Nombre                                                  | Destino        |
| ----- | ------------------------------------------------------- | -------------- |
| A     | projecte01.ddaw.es                                      | IP pública AWS |
| CNAME | api.projecte01.ddaw.es                                  | Backend EC2    |
| CNAME | [www.projecte01.ddaw.es](http://www.projecte01.ddaw.es) | Frontend       |

---

## Delegación DNS

La delegación incluye:

- Registros NS
- Configuración zona
- Nameservers

---

> 📸 **CAPTURA DNS**
>
> `![DNS](docs/dns-config.png)`

---

# ☁️ 2. ARQUITECTURA AWS

# Objetivo

Diseñar una arquitectura segura, desacoplada y escalable.

---

# 🧱 Infraestructura

## VPC propia

| Configuración    | Valor       |
| ---------------- | ----------- |
| CIDR             | 10.0.0.0/16 |
| AZs              | 2           |
| NAT Gateway      | Sí          |
| Internet Gateway | Sí          |

---

## Subredes Públicas

Utilizadas para:

- Application Load Balancer
- HTTPS
- Entrada tráfico

Ejemplo:

```txt
10.0.1.0/24
10.0.2.0/24
```

---

## Subredes Privadas Aplicación

Utilizadas para:

- EC2 Laravel
- Contenedores backend

Ejemplo:

```txt
10.0.10.0/24
10.0.11.0/24
```

---

## Subredes Privadas Datos

Utilizadas para:

- AWS RDS

Ejemplo:

```txt
10.0.20.0/24
10.0.21.0/24
```

---

# 🌍 3. CAPA EDGE Y HTTPS

## Punto único entrada

La infraestructura utiliza:

- Application Load Balancer (ALB)

Funciones:

- Terminación HTTPS
- Balanceo tráfico
- Redirección HTTP → HTTPS
- Reenvío backend

---

## HTTPS

Certificados válidos mediante:

```txt
Let's Encrypt
```

---

# ⚙️ 4. CAPA APLICACIÓN

## Backend Laravel

Tecnologías:

- Laravel 11
- PHP 8.2
- Sanctum
- MySQL
- Docker

---

## Escalabilidad

La arquitectura permite:

- Replicación horizontal
- Auto Scaling
- Balanceo carga

---

# 🗄️ 5. CAPA DATOS

## AWS RDS

| Característica   | Estado |
| ---------------- | ------ |
| Multi-AZ         | ✅     |
| Backups          | ✅     |
| Réplicas lectura | ✅     |
| Acceso público   | ❌     |

---

# 🔐 6. SEGURIDAD

## Security Groups

### ALB

| Puerto | Acceso  |
| ------ | ------- |
| 80     | Público |
| 443    | Público |

---

### Backend

| Puerto    | Acceso   |
| --------- | -------- |
| 80 / 8000 | Solo ALB |

---

### Base datos

| Puerto | Acceso       |
| ------ | ------------ |
| 3306   | Solo Backend |

---

# 🐳 7. DESARROLLO LOCAL CON DOCKER

## Objetivo

Permitir ejecutar el backend sin instalar PHP o MySQL localmente.

---

## Servicios Docker

- app
- nginx
- mysql

---

## Variables entorno

```env
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=printhub_db
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## Levantar entorno

```bash
docker-compose up -d --build
```

---

## Preparar Laravel

```bash
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

---

## Persistencia

La base de datos utiliza volúmenes Docker persistentes.

---

> 📸 **CAPTURA DOCKER BACKEND**
>
> `![Docker Backend](docs/docker-backend.png)`

---

# 🚀 8. CI/CD BACKEND

# Objetivo

Automatizar completamente el despliegue desde Git hasta producción.

---

# Pipeline

## 1. Instalación dependencias

```bash
composer install
```

---

## 2. Testing automático

```bash
php artisan test
```

---

## 3. Deploy automático

El despliegue se realiza automáticamente hacia AWS mediante:

- GitHub Actions
- SSH
- Deployer

---

## Usuario deployer

Para los despliegues automáticos se utiliza el usuario:

```txt
deployer
```

---

## Funciones del usuario deployer

- Lanzar deploys automáticos
- Ejecutar Deployer
- Gestionar releases
- Ejecutar migraciones
- Acceso SSH seguro

---

## Seguridad usuario deployer

- Sin permisos root
- SSH mediante claves
- Acceso limitado
- Usuario exclusivo CI/CD

---

## Flujo despliegue

```txt
GitHub Actions → SSH → deployer → Deployer → Producción
```

---

## Comando deploy

```bash
dep deploy production
```

---

## Migraciones automáticas

```bash
php artisan migrate --force
```

---

## Estructura releases

```txt
/var/www/printhub
├── current
├── releases
├── shared
└── .dep
```

---

# 🔄 9. INTEGRACIÓN CONTINUADA

## Flujo completo

```txt
Commit → Push → Pipeline → Test → Build → Deploy → Migraciones → Producción
```

---

# 👥 10. NORMAS CONTRIBUCIÓN

## GitFlow

| Rama       | Uso             |
| ---------- | --------------- |
| main       | Producción      |
| develop    | Integración     |
| feature/\* | Funcionalidades |

---

## Revisión código

- Pull Requests obligatorios
- Validación automática
- Revisión equipo

---

## Code Style

- PSR-12
- Código documentado
- Buenas prácticas Laravel

---

# 👤 11. USUARIOS PRUEBA

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

---

# 📜 12. LICENCIA

Proyecto desarrollado con fines educativos para el módulo DDAW + NUV.

---

# 👨‍💻 13. EQUIPO DESARROLLO

Proyecto desarrollado por el equipo PrintHub.

---

# 📌 14. ESTADO PROYECTO

| Característica | Estado |
| -------------- | ------ |
| API REST       | ✅     |
| Docker         | ✅     |
| AWS            | ✅     |
| HTTPS          | ✅     |
| CI/CD          | ✅     |
| Auto Deploy    | ✅     |
