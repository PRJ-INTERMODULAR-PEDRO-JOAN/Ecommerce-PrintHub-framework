# 🛍️ PrintHub – Framework de Comercio Electrónico

![Vista previa de la aplicación](./public/img/captura-inicio.jpg)

---

## 📖 Descripción

**PrintHub** es un framework de comercio electrónico desarrollado con Laravel y Vue.js.  
Ofrece una solución integral para la gestión de productos, carrito de compra, perfiles de usuario y un panel de administración para la gestión del inventario.

Está pensado como una base moderna y escalable para proyectos e-commerce.

---

## 📑 Tabla de Contenidos

1. Recursos y Tecnologías Utilizadas
2. Requisitos Previos
3. Instalación y Puesta en Marcha
4. Normas de Contribución
5. Créditos y Contribuidores
6. Licencia

---

## 🛠 Recursos y Tecnologías Utilizadas

**Backend**

- PHP 8.2
- Laravel 11

**Frontend**

- Vue.js
- HTML5
- CSS3
- Bootstrap 5

**Base de Datos**

- MySQL
- MariaDB

**Gestores de Dependencias**

- Composer (PHP)
- NPM (Node.js)

---

## ⚙️ Requisitos Previos

Antes de comenzar, asegúrate de tener instalado en tu equipo:

- Servidor local (XAMPP, Laragon o similar)
- PHP >= 8.2
- Composer
- Node.js y npm
- Git

---

## 🚀 Instalación y Puesta en Marcha

Sigue estos pasos en orden para desplegar la aplicación en tu entorno local:

### 1️⃣ Clonar el repositorio

Abre una terminal en la carpeta de tu servidor web y ejecuta el comando `git clone https://ruta-a-tu-repositorio.git` y luego accede al directorio con `cd ecommerce-printhub-framework`.

---

### 2️⃣ Instalar dependencias de PHP

Ejecuta el comando `composer install` para descargar todas las dependencias del backend.

---

### 3️⃣ Configurar las variables de entorno

Crea el archivo de configuración ejecutando `cp .env.example .env`.

Después, abre el archivo `.env` y configura los datos de tu base de datos en las variables `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.

---

### 4️⃣ Generar la clave de la aplicación

Ejecuta el comando `php artisan key:generate` para crear la clave de seguridad de la aplicación.

---

### 5️⃣ Ejecutar migraciones y seeders

Ejecuta `php artisan migrate --seed` para crear las tablas en la base de datos y cargar los datos iniciales de prueba.

---

### 6️⃣ Instalar dependencias del frontend

Ejecuta `npm install` para descargar las dependencias necesarias del frontend.

---

### 7️⃣ Compilar recursos y arrancar los servidores

Necesitarás abrir dos terminales distintas:

En la primera terminal, ejecuta `php artisan serve` para iniciar el servidor backend.

En la segunda terminal, ejecuta `npm run dev` para compilar los recursos del frontend.

---

## ✅ Acceso a la aplicación

Una vez ejecutados ambos procesos, la aplicación estará disponible en:

http://localhost:8000

---

## 🤝 Normas de Contribución

Para contribuir al proyecto:

1. Realiza un Fork del repositorio.
2. Crea una nueva rama con `git checkout -b feature/NuevaMejora`.
3. Escribe mensajes de commit claros y descriptivos.
4. Sube los cambios y abre un Pull Request.

---

## 👥 Créditos y Contribuidores

- Tu Nombre / Alias – Desarrollador Principal – Enlace a tu perfil
- Nombre de tu compañero/a – Desarrollador/a – Enlace a su perfil

---

## 📄 Licencia

Este proyecto está protegido bajo la Licencia MIT.

Puedes consultar los términos completos en el archivo `LICENSE` incluido en el repositorio.
