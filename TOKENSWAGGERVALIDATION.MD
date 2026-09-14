# VentasFix API — Swagger, métodos y validación de tokens

Guía de cómo está documentada la API con Swagger, qué endpoints existen, cuáles exigen token y cómo se valida ese token en cada petición.

- **Swagger UI:** http://localhost:8000/api/documentation
- **JSON OpenAPI generado:** `storage/api-docs/api-docs.json`
- **Usuarios de prueba:** `test@ventasfix.cl / Test1234` · `admin@ventasfix.cl / Admin1234`

---

## 1. Qué es Swagger acá y de dónde sale

Swagger (OpenAPI 3.0) es la documentación interactiva de la API: lista cada endpoint, qué recibe, qué devuelve y permite ejecutarlo desde el navegador sin Postman.

En este proyecto la genera el paquete **`darkaonline/l5-swagger`** leyendo **atributos PHP** `#[OA\...]` que van escritos **encima de cada método** de los controllers de `app/Http/Controllers/Api/`. Ejemplo real de `UserController@show`:

```php
#[OA\Get(path: '/api/users/{id}', tags: ['Usuarios'], summary: '1.2 Obtener un usuario por su ID', security: [['bearerAuth' => []]],
    parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
    responses: [
        new OA\Response(response: 200, description: 'Usuario encontrado'),
        new OA\Response(response: 404, description: 'No existe un usuario con ese id'),
        new OA\Response(response: 401, description: 'No autenticado'),
    ])]
public function show(int $id): JsonResponse
```

| Atributo | Qué define |
|---|---|
| `#[OA\Info]` | Título, versión y descripción de toda la API (está una sola vez, en `AuthController`) |
| `#[OA\Server]` | URL base (`L5_SWAGGER_CONST_HOST` del `.env`) |
| `#[OA\SecurityScheme]` | Cómo se manda el token: esquema **`bearerAuth`**, tipo `http`, `bearer`, formato `JWT` |
| `#[OA\Tag]` | Agrupa endpoints: Auth, Usuarios, Productos, Clientes, Dashboard |
| `#[OA\Get / Post / Put / Delete]` | Un endpoint: ruta, resumen, parámetros, body, respuestas |
| `security: [['bearerAuth' => []]]` | Marca que **ese endpoint exige token** (Swagger muestra el candado) |

**Regla importante:** los atributos van **antes** del método, nunca dentro. Si van dentro, Swagger no los ve.

Regeneración: con `L5_SWAGGER_GENERATE_ALWAYS=true` (en `.env`) la doc se reconstruye en cada carga de la página. Manualmente:

```bash
php artisan l5-swagger:generate
```

---

## 2. El token: qué es y qué lleva

La API usa **JWT (JSON Web Token)** con el paquete `php-open-source-saver/jwt-auth`. Un token es un string de 3 partes separadas por punto:

```
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9 . eyJpc3MiOiJodHRwOi8v...In0 . DHVK74PzBbAra-Yu_ZoJHeLScFPZ0L8mHYzVwMBjE5A
        HEADER                              PAYLOAD                          FIRMA
```

- **Header:** algoritmo (`HS256`) y tipo (`JWT`).
- **Payload** (decodificado de un token real de este sistema):

```json
{
  "iss": "http://localhost:8000/api/auth/login",   // quién lo emitió
  "iat": 1789263322,                                 // cuándo se emitió
  "exp": 1789266922,                                 // cuándo expira (iat + 60 min)
  "nbf": 1789263322,                                 // no válido antes de
  "jti": "mEX3m76I3P29ippj",                         // id único del token (lo usa la blacklist)
  "sub": "1",                                        // id del usuario dueño del token
  "prv": "23bd5c8949f600adb39e701c400872db7a5976f7"  // hash del modelo User
}
```

- **Firma:** HMAC-SHA256 de `header.payload` con `JWT_SECRET` (del `.env`). Si alguien cambia una letra del payload, la firma ya no coincide y el token se rechaza.

Configuración relevante (`config/jwt.php`):

| Clave | Valor | Significado |
|---|---|---|
| `ttl` | 60 min | Vida del token. Se entrega al cliente como `expires_in: 3600` (segundos) |
| `algo` | HS256 | Algoritmo de firma |
| `blacklist_enabled` | true | Al hacer logout el `jti` va a una lista negra (en cache) y el token deja de servir aunque no haya expirado |

El servidor **no guarda sesión**: cada petición trae el token y se valida ahí mismo (*stateless*). Por eso la misma API sirve para Softland o cualquier tercero.

---

## 3. Cómo obtener y usar el token en Swagger (paso a paso)

1. Abrir http://localhost:8000/api/documentation.
2. Desplegar **Auth → `POST /api/auth/login`** → **Try it out** → el body ya trae `test@ventasfix.cl / Test1234` → **Execute**.
3. En la respuesta `200` copiar el valor de `data.access_token` (sin comillas).
4. Clic en **Authorize** (botón con candado, arriba a la derecha) → pegar el token en el campo `bearerAuth` → **Authorize** → **Close**.
   - No hay que escribir la palabra `Bearer`: Swagger la agrega sola en la cabecera.
5. Desde ahora cada endpoint con candado envía `Authorization: Bearer <token>` automáticamente.
6. Para "cerrar sesión" desde Swagger: ejecutar `POST /api/auth/logout` (el token queda invalidado) y luego **Authorize → Logout** para que Swagger deje de enviarlo.

Con Postman o curl es la misma idea, la cabecera se pone a mano:

```bash
curl http://localhost:8000/api/users -H "Accept: application/json" -H "Authorization: Bearer <token>"
```

---

## 4. Cómo se valida el token en cada petición

Las rutas protegidas pasan por el middleware **`jwt.verify`** (`app/Http/Middleware/JwtMiddleware.php`, alias registrado en `bootstrap/app.php`). Orden de lo que hace:

```
Request → SetLocale (idioma) → jwt.verify → Controller
```

1. `JWTAuth::parseToken()` lee la cabecera `Authorization: Bearer <token>`. Si no viene → `JWTException`.
2. `->authenticate()` decodifica el token, **verifica la firma** con `JWT_SECRET`, **verifica `exp`/`nbf`**, revisa que el `jti` **no esté en la blacklist**, y carga el usuario cuyo id está en `sub` (`User::find(sub)`).
3. Si todo pasa, la petición sigue al controller. Si algo falla, responde **401** con el envelope y **no llega al controller**.

Casos y respuestas (todas con código HTTP 401):

| Situación | Excepción capturada | `message` |
|---|---|---|
| Sin cabecera `Authorization` | `JWTException` | `Token ausente.` |
| Token alterado / firma no coincide / logout previo (blacklist) | `TokenInvalidException` | `El token es inválido.` |
| Pasaron más de 60 min desde el login | `TokenExpiredException` | `El token expiró.` |
| Token válido pero el usuario fue eliminado | (`authenticate()` devuelve `false`) | `El usuario del token ya no existe.` |

El orden de los `catch` importa: `TokenExpiredException` y `TokenInvalidException` heredan de `JWTException`, por eso la genérica va al final.

Ejemplo de respuesta 401:

```json
{ "ok": false, "code": 401, "message": "Token ausente.", "errors": null }
```

---

## 5. Métodos de la API y qué token necesita cada uno

Todas las respuestas usan el mismo envelope (`app/Traits/ApiResponse.php`):

```json
{ "ok": true,  "code": 200, "message": "…", "data": … }          // éxito
{ "ok": false, "code": 4xx, "message": "…", "errors": … | null } // error
```

### Auth

| Método | Ruta | Token | Códigos | Descripción |
|---|---|---|---|---|
| POST | `/api/auth/register` | **No** | 201 · 422 | Registra un usuario (`@ventasfix.cl`, clave ≥ 8). La clave se guarda **cifrada con bcrypt** (cast `hashed` del modelo) |
| POST | `/api/auth/login` | **No** | 200 · 401 · 422 | Entrega `access_token`, `token_type: Bearer`, `expires_in: 3600` |
| GET | `/api/auth/me` | **Sí** | 200 · 401 | Usuario dueño del token (sin `password`, está en `Hidden`) |
| POST | `/api/auth/logout` | **Sí** | 200 · 401 | Invalida el token actual (blacklist) |

### 1. Usuarios — `#[OA\Tag('Usuarios')]`

| Método | Ruta | Token | Códigos | Requerimiento |
|---|---|---|---|---|
| GET | `/api/users` | Sí | 200 · 401 | 1.1 Listar todos |
| GET | `/api/users/{id}` | Sí | 200 · 404 · 401 | 1.2 Obtener por ID |
| POST | `/api/users` | Sí | 201 · 422 · 401 | 1.3 Agregar |
| PUT / PATCH | `/api/users/{id}` | Sí | 200 · 404 · 422 · 401 | 1.4 Actualizar (PATCH acepta parcial) |
| DELETE | `/api/users/{id}` | Sí | 200 · 404 · 401 | 1.5 Eliminar |

### 2. Productos — `#[OA\Tag('Productos')]`

| Método | Ruta | Token | Códigos | Requerimiento |
|---|---|---|---|---|
| GET | `/api/products` | Sí | 200 · 401 | 2.1 Listar todos |
| GET | `/api/products/{id}` | Sí | 200 · 404 · 401 | 2.2 Obtener por ID |
| POST | `/api/products` | Sí | 201 · 422 · 401 | 2.3 Agregar (`sale_price` se calcula solo: neto + 19 %) |
| PUT / PATCH | `/api/products/{id}` | Sí | 200 · 404 · 422 · 401 | 2.4 Actualizar (si cambia `net_price` se recalcula `sale_price`) |
| DELETE | `/api/products/{id}` | Sí | 200 · 404 · 401 | 2.5 Eliminar |

### 3. Clientes — `#[OA\Tag('Clientes')]`

| Método | Ruta | Token | Códigos | Requerimiento |
|---|---|---|---|---|
| GET | `/api/clients` | Sí | 200 · 401 | 3.1 Listar todos |
| GET | `/api/clients/{id}` | Sí | 200 · 404 · 401 | 3.2 Obtener por ID |
| POST | `/api/clients` | Sí | 201 · 422 · 401 | 3.3 Agregar |
| PUT / PATCH | `/api/clients/{id}` | Sí | 200 · 404 · 422 · 401 | 3.4 Actualizar |
| DELETE | `/api/clients/{id}` | Sí | 200 · 404 · 401 | 3.5 Eliminar |

### 4. Dashboard — `#[OA\Tag('Dashboard')]`

| Método | Ruta | Token | Códigos | Requerimiento |
|---|---|---|---|---|
| GET | `/api/dashboard` | Sí | 200 · 401 | 4.1–4.3 `{ users, products, clients }` |

### Errores generales (cualquier ruta `/api/*`)

| Código | Cuándo | `message` |
|---|---|---|
| 404 | Ruta que no existe o `{id}` no numérico (`/api/users/abc`) | `La ruta solicitada no existe en esta API.` |
| 405 | Método incorrecto (ej: `GET /api/auth/login`) | `Método no permitido para esta ruta.` |
| 422 | Validación del `FormRequest` fallida | `Los datos enviados no son válidos.` + `errors` por campo |

---

## 6. Ejemplos de respuestas reales

**Login OK (200):**

```json
{
  "ok": true, "code": 200, "message": "Inicio de sesión exitoso.",
  "data": { "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9…", "token_type": "Bearer", "expires_in": 3600 }
}
```

**Validación (422) — POST /api/users sin datos:**

```json
{
  "ok": false, "code": 422, "message": "Los datos enviados no son válidos.",
  "errors": {
    "rut": ["El campo RUT es obligatorio."],
    "first_name": ["El campo nombre es obligatorio."],
    "last_name": ["El campo apellido es obligatorio."],
    "email": ["El campo correo electrónico es obligatorio."],
    "password": ["El campo contraseña es obligatorio."]
  }
}
```

**No encontrado (404):**

```json
{ "ok": false, "code": 404, "message": "No existe un usuario con el id 9999.", "errors": null }
```

**Después de logout, mismo token (401):**

```json
{ "ok": false, "code": 401, "message": "El token es inválido.", "errors": null }
```

---

## 7. Idioma de los mensajes

Los textos salen en español por defecto y en inglés si el cliente lo pide, sin cambiar nada de la API (middleware `SetLocale`):

- `?lang=en` en la URL, o
- cabecera `Accept-Language: en`.

```
GET /api/users/9999?lang=en → "There is no user with id 9999."
```

---

## 8. Secuencia de prueba recomendada (video / Postman)

| # | Acción | Esperado |
|---|---|---|
| 1 | `GET /api/users` sin token | 401 `Token ausente.` |
| 2 | `POST /api/auth/login` con clave mala | 401 `Credenciales inválidas.` |
| 3 | `POST /api/auth/login` correcto | 200 + `access_token` |
| 4 | **Authorize** con el token → `GET /api/auth/me` | 200, usuario sin `password` |
| 5 | `POST /api/users` con `{}` | 422 con 5 errores |
| 6 | `POST /api/users` válido | 201 (`code: 201` en el cuerpo) |
| 7 | `GET /api/users/{id}` del creado | 200 |
| 8 | `PATCH /api/users/{id}` con `{"first_name": "Nuevo"}` | 200, campo actualizado |
| 9 | `GET /api/users/9999` | 404 |
| 10 | `DELETE /api/users/{id}` | 200 · repetir → 404 |
| 11 | `POST /api/products` con `net_price: 10000` | 201 con `sale_price: 11900` |
| 12 | `GET /api/dashboard` | 200 `{ users, products, clients }` |
| 13 | `POST /api/auth/logout` | 200 |
| 14 | `GET /api/auth/me` con el mismo token | 401 `El token es inválido.` |

---

## 9. Problemas comunes

| Síntoma | Causa | Solución |
|---|---|---|
| 401 `Token ausente.` en Swagger aunque hice login | No se presionó **Authorize** o se cerró sin confirmar | Authorize → pegar token → Authorize |
| 401 `El token es inválido.` recién logueado | Se pegó el token con comillas, con espacios, o con la palabra `Bearer` dos veces | Pegar solo el string `eyJ…` |
| 401 `El token expiró.` | Pasaron 60 min | Volver a hacer login |
| 401 después de logout | El token quedó en la blacklist | Login de nuevo (es el comportamiento esperado) |
| 405 | Método equivocado (ej: GET a login) | Usar el método de la tabla |
| Swagger no muestra un endpoint nuevo | Atributos `#[OA\…]` dentro del método o error de sintaxis en ellos | Ponerlos encima del método y correr `php artisan l5-swagger:generate` para ver el error |
| Swagger apunta a otro host | `L5_SWAGGER_CONST_HOST` distinto de donde corre `artisan serve` | Ajustar en `.env` |

---

## 10. Archivos involucrados

| Archivo | Rol |
|---|---|
| `config/jwt.php` · `.env` (`JWT_SECRET`, `JWT_ALGO`) | Configuración del token |
| `config/auth.php` → guard `api` (driver `jwt`) | `auth('api')` = `JWTGuard` (`attempt`, `user`, `logout`) |
| `app/Models/User.php` (`JWTSubject`) | `getJWTIdentifier()` → el `sub` del payload |
| `app/Http/Middleware/JwtMiddleware.php` (alias `jwt.verify`) | Validación del token en cada request protegido |
| `app/Http/Controllers/Api/AuthController.php` | register / login / me / logout + `#[OA\Info]`, `#[OA\SecurityScheme]` |
| `app/Http/Controllers/Api/{User,Product,Client,Dashboard}Controller.php` | Endpoints CRUD con sus `#[OA\…]` |
| `routes/api.php` | Rutas: públicas (register, login) y grupo `jwt.verify` (todo lo demás) |
| `app/Traits/ApiResponse.php` | Envelope uniforme + traducción de mensajes |
| `config/l5-swagger.php` · `storage/api-docs/api-docs.json` | Configuración y JSON generado de Swagger |
