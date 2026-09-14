# VentasFix — Backoffice + API (Laravel 13)

Microservicio de manejo de carro de compra para **VentasFix**: un backoffice web para los trabajadores (login, dashboard y mantenedores de usuarios, productos y clientes) y una **API REST con JWT** para que sistemas de terceros (Softland) consuman los mismos datos.

> Examen final · Desarrollo de Software Web I · Instituto Profesional San Sebastián
> Estudiante: **Drayer Durán** — Repositorio: https://github.com/Drayer-dm/ventasfix

---

## Índice

1. [Inicio rápido (5 minutos)](#1-inicio-rápido-5-minutos)
2. [Requisitos](#2-requisitos)
3. [Instalación paso a paso](#3-instalación-paso-a-paso)
4. [Cómo usarlo](#4-cómo-usarlo)
5. [Credenciales de prueba](#5-credenciales-de-prueba)
6. [Qué hay adentro (arquitectura)](#6-qué-hay-adentro-arquitectura)
7. [Errores y problemas comunes — y cómo arreglarlos](#7-errores-y-problemas-comunes--y-cómo-arreglarlos)
8. [Comandos útiles](#8-comandos-útiles)
9. [Documentación adicional](#9-documentación-adicional)

---

## 1. Inicio rápido (5 minutos)

Si ya tienes PHP, Composer y Node instalados, esto es todo:

```bash
composer install
copy .env.example .env          # en Linux/Mac: cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
npm install
npm run build
php artisan serve
```

Luego abre:

| Qué | URL |
|---|---|
| Backoffice (web) | http://localhost:8000 |
| Documentación de la API (Swagger) | http://localhost:8000/api/documentation |

Entra con **`admin@ventasfix.cl` / `Admin1234`**.

Si algo falla, ve directo a la sección [7. Errores y problemas comunes](#7-errores-y-problemas-comunes--y-cómo-arreglarlos).

---

## 2. Requisitos

| Herramienta | Versión mínima | Cómo verificar | Dónde descargar |
|---|---|---|---|
| **PHP** | 8.3 (probado con 8.5) | `php -v` | https://php.new (instalador oficial de Laravel) o https://windows.php.net |
| **Composer** | 2.x | `composer -V` | https://getcomposer.org |
| **Node.js** + npm | 20 (probado con 26) | `node -v` y `npm -v` | https://nodejs.org |
| **Git** (opcional, para clonar) | cualquiera | `git --version` | https://git-scm.com |

**Extensiones de PHP necesarias** (vienen activas en la instalación de php.new; en XAMPP hay que revisarlas en `php.ini`):

```
pdo_sqlite   sqlite3   mbstring   openssl   fileinfo   ctype   json   tokenizer   xml
```

Para ver cuáles tienes activas: `php -m`.

> **No necesitas MySQL.** El proyecto usa **SQLite**: la base de datos es un archivo (`database/database.sqlite`) que se crea solo.

---

## 3. Instalación paso a paso

### 3.1 Obtener el código

Desde el zip: descomprime `EXF_DURAN_DRAYER` y entra a la carpeta `ventasfix`.
Desde Git:

```bash
git clone https://github.com/Drayer-dm/ventasfix.git
cd ventasfix
```

**Todos los comandos que siguen se ejecutan dentro de la carpeta `ventasfix`** (donde está el archivo `artisan`).

### 3.2 Dependencias de PHP

```bash
composer install
```

Descarga Laravel y los paquetes en la carpeta `vendor/` (tarda 1–3 minutos la primera vez).

### 3.3 Archivo de configuración `.env`

```bash
copy .env.example .env
```

(En Linux/Mac: `cp .env.example .env`). El `.env` guarda la configuración local: nombre de la app, base de datos, claves. **Nunca se sube al repositorio.**

### 3.4 Claves de la aplicación

```bash
php artisan key:generate
php artisan jwt:secret
```

- `key:generate` → `APP_KEY`, cifra sesiones y cookies.
- `jwt:secret` → `JWT_SECRET`, firma los tokens de la API. **Sin esto la API no puede emitir ni validar tokens.**

### 3.5 Base de datos

```bash
php artisan migrate:fresh --seed
```

Crea el archivo SQLite (si no existe), las tablas `users`, `products`, `clients` (y las internas de Laravel) y carga datos de prueba: 2 usuarios, 3 productos y 2 clientes.

> Si te pregunta *"Do you really wish to run this command?"* responde **yes** (o agrega `--force`).

### 3.6 Estilos y JavaScript (Vite + Tailwind)

```bash
npm install
npm run build
```

Compila el CSS/JS en `public/build/`. Solo hay que repetir `npm run build` si modificas archivos de `resources/css` o `resources/js`.

### 3.7 Levantar el servidor

```bash
php artisan serve
```

Queda escuchando en http://localhost:8000. Para detenerlo: `Ctrl + C`.

---

## 4. Cómo usarlo

### 4.1 Backoffice web

1. Abre http://localhost:8000 → te lleva al **login**.
2. Entra con una de las [credenciales de prueba](#5-credenciales-de-prueba).
3. Menú lateral: **Dashboard** (contadores), **Usuarios**, **Productos**, **Clientes**. Cada mantenedor tiene: listar, ver, crear, editar y eliminar.
4. Arriba a la derecha: **modo claro/oscuro**. En el sidebar: botón para **colapsar** el menú. Ambas preferencias se recuerdan.
5. Los formularios validan en el navegador antes de enviar y el servidor vuelve a validar (no se guardan datos vacíos ni inválidos).

### 4.2 API (Swagger)

1. Abre http://localhost:8000/api/documentation.
2. **Auth → POST /api/auth/login → Try it out → Execute** (el ejemplo ya trae `test@ventasfix.cl / Test1234`).
3. Copia el `access_token` de la respuesta.
4. Botón **Authorize** (candado arriba a la derecha) → pega el token → Authorize → Close.
5. Ya puedes ejecutar cualquier endpoint de Usuarios, Productos, Clientes y Dashboard.

Con Postman o curl, agrega la cabecera `Authorization: Bearer <token>` y `Accept: application/json`.

Guía detallada de tokens, endpoints y códigos de respuesta: **[TOKENSWAGGERVALIDATION.MD](TOKENSWAGGERVALIDATION.MD)**.

---

## 5. Credenciales de prueba

| Correo | Contraseña | Uso |
|---|---|---|
| `admin@ventasfix.cl` | `Admin1234` | Administrador |
| `test@ventasfix.cl` | `Test1234` | Pruebas de API / demos |

No hay roles: todo usuario autenticado administra todo el sistema (así lo define el enunciado). Todos los correos deben terminar en **`@ventasfix.cl`**. Las contraseñas se guardan **cifradas con bcrypt**.

Para volver a los datos iniciales en cualquier momento:

```bash
php artisan migrate:fresh --seed
```

---

## 6. Qué hay adentro (arquitectura)

### 6.1 Flujo de una petición

```
Navegador ──► routes/web.php ──► Controllers/Web/*  ──┐
                                                       ├──► Requests (validación) ──► Services ──► Models ──► SQLite
Softland  ──► routes/api.php ──► Controllers/Api/*  ──┘        (misma lógica para web y API)
```

- **Controllers web** devuelven vistas Blade y redirecciones; **controllers API** devuelven JSON con código HTTP explícito. Ambos usan **los mismos** `FormRequest` (reglas) y `Services` (acceso a datos): una sola lógica, dos entradas.
- **Services** (`app/Services`): `UserService`, `ProductService`, `ClientService`, `DashboardService`. Los controllers no tocan Eloquent directo.
- **Models** (`app/Models`): `User` (JWT + password cifrada), `Product` (calcula `sale_price = net_price × 1,19` automáticamente al guardar), `Client`.
- **Middleware**: `jwt.verify` protege la API; `auth` protege la web; `SetLocale` elige idioma (es/en) por petición.

### 6.2 Estructura de carpetas relevante

```
ventasfix/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/        AuthController, UserController, ProductController, ClientController, DashboardController
│   │   │   ├── Web/        DashboardController, UserController, ProductController, ClientController
│   │   │   └── Auth/       SessionController (login/logout web)
│   │   ├── Middleware/     JwtMiddleware, SetLocale
│   │   └── Requests/       ApiFormRequest (base) + {User,Product,Client}/{Store,Update}*Request
│   ├── Models/             User, Product, Client
│   ├── Services/           UserService, ProductService, ClientService, DashboardService
│   └── Traits/             ApiResponse (envelope JSON uniforme)
├── database/
│   ├── migrations/         users, products, clients
│   └── seeders/            DatabaseSeeder (datos de prueba)
├── lang/                   es/ y en/ (validación) · en.json (mensajes de la API)
├── resources/
│   ├── css/app.css         tokens de diseño (claro/oscuro, neumorfismo)
│   ├── js/app.js           tema, sidebar, validación en el navegador, previews
│   └── views/
│       ├── components/     atoms/ · molecules/ · organisms/ · templates/   (Atomic Design)
│       ├── pages/          auth/ · dashboard/ · users/ · products/ · clients/
│       └── errors/         404, 419
├── routes/                 web.php (29 rutas) · api.php (20 rutas)
├── public/images/products/ imágenes subidas desde el backoffice
└── storage/api-docs/       JSON de Swagger generado
```

### 6.3 Front: Atomic Design

Las **páginas no tienen HTML propio**: solo componen un *template* con *organisms*. Cada nivel usa únicamente el nivel inferior:

| Nivel | Ejemplos | Uso en Blade |
|---|---|---|
| **atoms** | `input`, `button`, `badge`, `avatar`, iconos | `<x-atoms.form.input />` |
| **molecules** | `field` (label+input+error), `stat-card`, `sidebar-item` | `<x-molecules.form.field />` |
| **organisms** | `sidebar`, `user.table`, `product.form`, `login-form` | `<x-organisms.user.table :users="$users" />` |
| **templates** | `app` (sidebar + topbar + contenido), `auth` (login) | `<x-templates.app title="Usuarios">` |
| **pages** | `pages/users/index.blade.php` | lo que devuelve el controller |

El tema claro/oscuro se resuelve con **variables CSS** (`resources/css/app.css`): los componentes nunca usan colores directos, así que cambiar el diseño completo es editar un solo archivo.

### 6.4 Rúbrica → dónde está cada cosa

| Ítem | Dónde verlo |
|---|---|
| Rutas | `routes/web.php`, `routes/api.php` · `php artisan route:list` |
| Modelos / migraciones | `app/Models`, `database/migrations` |
| Controladores ↔ Servicios | `app/Http/Controllers/**` inyectan `app/Services/*` por constructor |
| Vistas (login, dashboard, 3 mantenedores) | `resources/views/pages/**` |
| Componentes reutilizables | `resources/views/components/**` |
| BD en variables de entorno | `.env` → `DB_CONNECTION=sqlite` |
| Login + middleware | `Auth/SessionController` + `auth` (web) · `Api/AuthController` + `JwtMiddleware` (API) |
| Registro con clave cifrada | `POST /api/auth/register` y `POST /users` → `User` con cast `hashed` |
| CRUD con códigos HTTP | `Controllers/Api/*`: 200 · 201 · 404 · 422 · 401 |
| Datos obligatorios / no vacíos | `app/Http/Requests/**` (servidor) + `resources/js/app.js` (navegador) |

---

## 7. Errores y problemas comunes — y cómo arreglarlos

Busca el mensaje que ves en pantalla. Si no está, revisa `storage/logs/laravel.log`.

### Al instalar

| Mensaje / síntoma | Causa | Solución |
|---|---|---|
| `'php' no se reconoce como un comando` | PHP no está en el PATH | Instala con https://php.new (agrega PHP al PATH solo) o agrega la carpeta de `php.exe` a las variables de entorno. Cierra y abre la terminal |
| `'composer' no se reconoce…` / `'npm' no se reconoce…` | Composer o Node no instalados o terminal abierta antes de instalarlos | Instálalos y **abre una terminal nueva** |
| `Your lock file does not contain a compatible set of packages` o `requires php ^8.3` | PHP muy antiguo | Actualiza a PHP 8.3 o superior (`php -v`) |
| `could not find driver` (al migrar) | Falta la extensión `pdo_sqlite` | Abre `php.ini`, busca `;extension=pdo_sqlite` y `;extension=sqlite3`, quita el `;`, guarda y reinicia la terminal |
| `Database file at path [...database.sqlite] does not exist` | El archivo SQLite no existe | Créalo vacío: `type nul > database\database.sqlite` (Windows) o `touch database/database.sqlite` (Linux/Mac). Luego `php artisan migrate:fresh --seed` |
| `No application encryption key has been specified` | Falta `APP_KEY` | `php artisan key:generate` |
| `npm ERR! ... EBADENGINE` / `Vite requires Node.js version 20+` | Node antiguo | Actualiza Node a 20 o superior |
| `npm install` se queda pegado o falla por red | Proxy / red | Reintenta; si estás tras proxy: `npm config set proxy http://...` |

### Al abrir la web

| Mensaje / síntoma | Causa | Solución |
|---|---|---|
| **Página sin estilos** (todo blanco, texto plano, logo gigante) | Falta compilar los assets **o** quedó un archivo `public/hot` de un `npm run dev` que se cerró mal | `npm run build`. Si persiste, borra el archivo `public/hot` y recarga. (El proyecto lo detecta y lo borra solo si el servidor de Vite no responde, pero si el servidor sigue vivo a medias, bórralo a mano) |
| `Vite manifest not found at: .../public/build/manifest.json` | No se ejecutó `npm run build` | `npm install` y luego `npm run build` |
| `SQLSTATE[HY000]: General error: 1 no such table: users` | Las tablas no existen | `php artisan migrate:fresh --seed` |
| `SQLSTATE... no such table: sessions` | Igual que arriba (la sesión se guarda en BD) | `php artisan migrate:fresh --seed` |
| **419 · La página expiró** al enviar un formulario | Token CSRF vencido (formulario abierto mucho rato) o sesión reiniciada | Recarga la página y vuelve a enviar |
| **404 · Página no encontrada** | La URL no existe o el registro fue eliminado | Vuelve al dashboard desde el botón de la página |
| `The stream or file "storage/logs/laravel.log" could not be opened` | Sin permisos de escritura en `storage/` | Windows: dale permisos a la carpeta. Linux/Mac: `chmod -R 775 storage bootstrap/cache` |
| `Target class [...] does not exist` o `Class "..." not found` después de copiar/mover archivos | Autoload desactualizado | `composer dump-autoload` |
| Cambios en Blade/CSS que no se ven | Caché de vistas o assets sin recompilar | `php artisan view:clear` y, si tocaste CSS/JS, `npm run build` |
| `Address already in use` / `Failed to listen on 127.0.0.1:8000` | Otro proceso usa el puerto 8000 | `php artisan serve --port=8001` (y cambia `L5_SWAGGER_CONST_HOST` en `.env` si usas Swagger) |
| La imagen de un producto no se ve | Se borró el archivo de `public/images/products/` o la ruta guardada es externa | Edita el producto y sube la imagen de nuevo |
| Los mensajes salen en inglés | El navegador declara `Accept-Language: en` | Es el multi-idioma funcionando. Para forzar español agrega `?lang=es` a la URL o cambia el idioma del navegador |

### Al usar la API / Swagger

| Mensaje / síntoma | Causa | Solución |
|---|---|---|
| `401 · Token ausente.` | No se envió la cabecera `Authorization` | En Swagger: botón **Authorize** y pega el token. En Postman: pestaña *Authorization → Bearer Token* |
| `401 · El token es inválido.` | Token mal pegado (con comillas, espacios, `Bearer` repetido), alterado, o **ya se hizo logout** con él | Haz login de nuevo y pega solo el string `eyJ…` |
| `401 · El token expiró.` | Pasaron 60 minutos desde el login | Haz login de nuevo |
| `401 · Credenciales inválidas.` en login | Correo o clave incorrectos | Usa las [credenciales de prueba](#5-credenciales-de-prueba) |
| `422 · Los datos enviados no son válidos.` | Faltan campos o tienen formato inválido (el detalle viene en `errors`) | Corrige los campos indicados. Recuerda: correo `@ventasfix.cl`, clave ≥ 8, `net_price ≥ 1`, umbrales de stock `mínimo ≤ bajo ≤ alto` |
| `404 · No existe un ... con el id N` | Ese id no está en la BD | Consulta primero el listado (`GET /api/...`) |
| `404 · La ruta solicitada no existe en esta API.` | URL mal escrita o `{id}` no numérico | Revisa la ruta en Swagger |
| `405 · Método no permitido para esta ruta.` | Método HTTP equivocado (ej. GET a `/api/auth/login`) | Usa el método que indica Swagger |
| Swagger carga pero **no lista endpoints** | Error en las anotaciones `#[OA\...]` o doc no generada | `php artisan l5-swagger:generate` y lee el error que imprime |
| Swagger llama a otro host/puerto | `L5_SWAGGER_CONST_HOST` no coincide con donde corre `artisan serve` | Ajusta la variable en `.env` |
| `Could not create token` / `secret is not set` | Falta `JWT_SECRET` | `php artisan jwt:secret` |
| Tildes rotas (`PÃ©rez`) al enviar JSON desde la terminal de Windows | La consola convierte el texto a otra codificación | Envía el body desde un archivo UTF-8 (`curl --data-binary @body.json`) o usa Swagger/Postman |

### Al desarrollar

| Situación | Qué hacer |
|---|---|
| Quiero ver los cambios de CSS/JS al instante | En otra terminal: `npm run dev` (recarga en vivo). **Ciérralo con Ctrl+C**; si lo matas de otra forma queda `public/hot` y las páginas salen sin estilos |
| Quiero todo con un solo comando (servidor + vite + logs) | `composer run dev` |
| Quiero regenerar Swagger | `php artisan l5-swagger:generate` (o recarga la página, se regenera sola en local) |
| Quiero limpiar todas las cachés | `php artisan optimize:clear` |
| Agregué una migración | `php artisan migrate` (o `migrate:fresh --seed` para partir de cero) |

---

## 8. Comandos útiles

| Comando | Para qué |
|---|---|
| `php artisan serve` | Levantar el servidor en http://localhost:8000 |
| `php artisan migrate:fresh --seed` | Reiniciar la base de datos con datos de prueba |
| `php artisan route:list` | Ver todas las rutas (web + API) |
| `php artisan route:list --path=api` | Solo las rutas de la API |
| `php artisan l5-swagger:generate` | Regenerar la documentación Swagger |
| `php artisan jwt:secret` | Generar/regenerar la clave de los tokens |
| `php artisan optimize:clear` | Limpiar cachés de config, rutas y vistas |
| `php artisan tinker` | Consola interactiva (ej. `App\Models\User::count()`) |
| `npm run build` | Compilar CSS/JS para producción |
| `npm run dev` | Compilar en vivo mientras desarrollas |
| `composer run dev` | Servidor + Vite + logs a la vez |

---

## 9. Documentación adicional

- **[TOKENSWAGGERVALIDATION.MD](TOKENSWAGGERVALIDATION.MD)** — Swagger, todos los endpoints con sus códigos, cómo se valida el token JWT en cada petición y secuencia de prueba recomendada.
- Swagger en vivo: http://localhost:8000/api/documentation
- Laravel 13: https://laravel.com/docs · Tailwind CSS 4: https://tailwindcss.com/docs · jwt-auth: https://laravel-jwt-auth.readthedocs.io · L5-Swagger: https://github.com/DarkaOnLine/L5-Swagger
