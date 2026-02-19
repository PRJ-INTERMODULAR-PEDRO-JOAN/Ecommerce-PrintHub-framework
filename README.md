# PrintHub E-Commerce Framework

![Vista prèvia de l'aplicació](./public/img/captura-inicio.jpg)

## 📖 Descripció

[cite_start]PrintHub és un framework de comerç electrònic desenvolupat amb Laravel i Vue.js[cite: 279]. Aquest projecte ofereix una solució completa per a la gestió de vendes, carret de la compra, usuaris i catàleg de productes.

## 📑 Taula de Continguts

1. [Recursos i Tecnologies utilitzades](#recursos-i-tecnologies-utilitzades)
2. [Requisits previs](#requisits-previs)
3. [Procés d'Instal·lació i Posada en marxa](#procés-dinstallació-i-posada-en-marxa)
4. [Normes de Contribució](#normes-de-contribució)
5. [Crèdits i Contribuïdors](#crèdits-i-contribuïdors)
6. [Llicència](#llicència)

---

## 🛠 Recursos i Tecnologies utilitzades

[cite_start]Per al desenvolupament d'aquest projecte s'han utilitzat les següents eines[cite: 282]:

- **Backend:** PHP 8.2 i Laravel 11
- **Frontend:** Vue.js, HTML5, CSS3 i Bootstrap 5
- **Base de Dades:** MySQL / MariaDB
- **Gestor de dependències:** Composer (PHP) i NPM (Node.js)

## ⚙️ Requisits previs

[cite_start]Abans d'instal·lar el projecte, assegura't de tenir instal·lats els següents programes en el teu entorn de desenvolupament local[cite: 282]:

- Servidor local (XAMPP, Laragon, o Docker).
- PHP >= 8.2.
- Composer.
- Node.js i npm.
- Git.

## 🚀 Procés d'Instal·lació i Posada en marxa

[cite_start]Segueix aquests passos en ordre per desplegar i instal·lar l'aplicació en el teu entorn local[cite: 281]:

**1. Clonar el repositori**
Obre el teu terminal, dirigeix-te a la carpeta del teu servidor web (per exemple `htdocs` a XAMPP) i clona el projecte:

```bash
git clone [https://ruta-al-teu-repositori.git](https://ruta-al-teu-repositori.git)
cd ecommerce-printhub-framework
2. Instal·lar les dependències del Backend (PHP/Laravel)
Descarrega i instal·la totes les llibreries necessàries executant Composer:

Bash
composer install
3. Configurar les variables d'entorn
Crea el teu propi fitxer de configuració copiant el fitxer d'exemple que ve al repositori:

Bash
cp .env.example .env
Nota important: Obre el fitxer .env en el teu editor de codi i configura les credencials de la teva base de dades local (modifica DB_DATABASE, DB_USERNAME i DB_PASSWORD).

4. Generar la clau de l'aplicació
Genera la clau d'encriptació requerida per Laravel:

Bash
php artisan key:generate
5. Executar les migracions i seeders
Crea les taules a la teva base de dades i omple-les amb les dades de prova inicials (productes, usuaris, etc.):

Bash
php artisan migrate --seed
6. Instal·lar dependències del Frontend (Vue.js/Bootstrap)
Descarrega els paquets de Node necessaris per compilar les vistes:

Bash
npm install
```
