# Sprint 3: Creació del projecte Laravel i API

Aquest document resumeix l'arquitectura, rutes i decisions tècniques implementades durant el Sprint 3 per a la migració del projecte a Laravel.

---

## C1 – Configuració de l'Entorn

S'ha inicialitzat el projecte Laravel dins del directori `laravel/`.

- **Base de Dades:** Configuració de l'arxiu `.env` per connectar a la mateixa instància MySQL utilitzada pel projecte legacy.
- **Docker:** S'ha integrat l'entorn de contenidors _(Indicar aquí l'opció escollida: "utilitzant el docker-compose existent del landing" o "mitjançant Laravel Sail dins del directori del projecte")_.

**Fitxers clau:**

- `laravel/.env`
- `laravel/config/database.php`
- `docker-compose.yml`

---

## C2 – Model de Dades i Migracions

S'ha traslladat l'esquema de dades a MySQL mitjançant migracions de Laravel.

### Taula `products`

Camps implementats:

- `id` (PK)
- `sku` (String, Índex únic)
- `name` (String)
- `description` (Text)
- `price` (Decimal/Float)
- `stock` (Integer)
- `image` (String)
- `category` (String)
- `timestamps` (`created_at`, `updated_at`)

### Taula `users`

S'ha utilitzat la migració estàndard de Laravel (`users`), compatible amb els usuaris existents.

**Dades de prova (Seeding):**
S'ha creat `ProductSeeder` per poblar la taula de productes amb dades inicials per a desenvolupament.

---

## C3 – Autenticació (Laravel Breeze)

S'ha implementat **Laravel Breeze** (versió Blade) per gestionar el flux d'autenticació complet.

### Rutes d'Autenticació

| Mètode | URI         | Descripció                             |
| :----- | :---------- | :------------------------------------- |
| GET    | `/register` | Vista del formulari de registre.       |
| POST   | `/register` | Acció de registrar un nou usuari.      |
| GET    | `/login`    | Vista del formulari d'inici de sessió. |
| POST   | `/login`    | Acció d'autenticar l'usuari.           |
| POST   | `/logout`   | Tancar sessió.                         |

> **Nota Comparativa (Sprint 2 vs Sprint 3):**
> En el Sprint 2, l'autenticació es gestionava manualment mitjançant `$_SESSION` de PHP i cookies pròpies. En aquest Sprint, Breeze utilitza el sistema de **Middleware** i **Guards** de Laravel, oferint una gestió automàtica de sessions segures, protecció CSRF i hashing de contrasenyes, simplificant el manteniment i augmentant la seguretat.

---

## C4 – Importació d'Excel

S'ha desenvolupat un mecanisme per importar productes massivament des d'un fitxer Excel.

- **Implementació:** _(Indicar si és via "Command" o "Controlador web")_.
- **Lògica:** El sistema llegeix l'arxiu, valida les dades (camps obligatoris com `sku`, `price`, `stock` i formats numèrics) i insereix o actualitza els registres a la BBDD.
- **Logs:** Es genera un resum de l'operació (línies processades i errors trobats).

**Fitxers clau:** `laravel/app/Imports/` o `laravel/app/Console/Commands/`.

---

## C5 – Vistes i API

S'ha creat la interfície visual per al llistat de productes i s'ha exposat la primera versió de l'API.

### Rutes Web (`routes/web.php`)

| Mètode | URI          | Descripció                                                                                          |
| :----- | :----------- | :-------------------------------------------------------------------------------------------------- |
| GET    | `/productes` | Llistat públic de productes. Utilitza una vista Blade amb estils heretats/adaptats del front antic. |

### Rutes API (`routes/api.php`)

Endpoints JSON preparats per al futur client SPA (Vue).

| Mètode | URI             | Descripció                                              |
| :----- | :-------------- | :------------------------------------------------------ |
| GET    | `/api/products` | Retorna el llistat complet de productes en format JSON. |
| GET    | `/api/comments` | (Opcional) Llistat de comentaris.                       |
| POST   | `/api/comments` | Emmagatzemar un nou comentari/valoració.                |

---

## C6 – Validacions i Comentaris (Client-Side)

S'ha integrat lògica JavaScript al frontend per gestionar la interactivitat abans de l'arribada de la SPA.

- **Comentaris/Valoracions:** S'ha afegit un formulari a la vista de productes que envia les dades a l'API mitjançant `Fetch` o `AJAX`.
- **Validacions:**
    - **Auth:** Gestionada per Breeze al servidor.
    - **Formularis:** JavaScript valida al client que els camps obligatoris tinguin contingut i que el rating estigui dins del rang permès abans d'enviar la petició.

---

## C7 – Testing

S'han creat proves automatitzades (`Feature Tests`) per assegurar la qualitat de l'API.

**Tests realitzats:**

1. **API Productes:** Verificació que l'endpoint `/api/products` retorna codi 200 i l'estructura de dades esperada.
2. **Comentaris:**
    - Test d'inserció correcta (validant bases de dades).
    - Test de validació (intentar enviar sense camps obligatoris).
3. **Importació:** Verificació bàsica del procés de càrrega d'Excel.

**Ubicació:** `laravel/tests/Feature/`
