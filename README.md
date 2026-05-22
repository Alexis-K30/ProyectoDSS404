# Proyecto DSS 404 - Tienda

API en Laravel para gestionar usuarios, categorias, productos, pedidos e items de pedido de una tienda.

## Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- Git
- MySQL/MariaDB opcional si no quieres usar SQLite

## Instalacion

Desde PowerShell, entra a la carpeta del proyecto:

```powershell
cd C:\Users\USER\Music\ProyectoDSS404-Alexis
```

Instala las dependencias:

```powershell
composer install
npm install
```

Si no existe el archivo `.env`, crealo desde el ejemplo:

```powershell
Copy-Item .env.example .env
```

Genera la key de Laravel:

```powershell
php artisan key:generate
```

## Base De Datos

### Opcion rapida con SQLite

En `.env`, usa:

```env
DB_CONNECTION=sqlite
```

Verifica que exista el archivo:

```powershell
New-Item -ItemType File -Force database\database.sqlite
```

Crea las tablas y datos de prueba:

```powershell
php artisan migrate:fresh --seed
```

Tambien se incluye un SQL de referencia en `database/dss404_seed.sql` con las tablas principales y datos generados por los seeders. Para ejecutar el proyecto se recomienda usar las migraciones y seeders, porque mantienen la base sincronizada con el codigo.

### Opcion con MySQL

Crea una base de datos vacia, por ejemplo `laravel`, y configura `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Luego ejecuta:

```powershell
php artisan migrate:fresh --seed
```

## Ejecutar El Proyecto

Levanta el servidor local:

```powershell
php artisan serve
```

La app quedara disponible en:

```text
http://127.0.0.1:8000
```

## Probar La API

Ver rutas disponibles:

```powershell
php artisan route:list --path=api/v1
```

Login de prueba:

```text
POST http://127.0.0.1:8000/api/v1/auth/login
```

Body JSON:

```json
{
  "email": "admin@tienda.com",
  "password": "admin1234"
}
```

Usa el token recibido como Bearer Token para probar rutas protegidas.

## Rutas de la API (para el Frontend de React)

Todas las peticiones a la API deben usar el prefijo `/api/v1` sobre la dirección del servidor (por defecto, `http://127.0.0.1:8000/api/v1`).

### Autenticación y Cabeceras

Para consumir las rutas protegidas, el frontend debe enviar el Bearer Token en la cabecera `Authorization` junto con la cabecera `Accept`:

```http
Authorization: Bearer <tu_token_recibido>
Accept: application/json
```

---

### 1. Rutas Públicas (No requieren autenticación)

| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **POST** | `/auth/register` | `AuthController@register` | Registra un nuevo usuario en el sistema. |
| **POST** | `/auth/login` | `AuthController@login` | Inicia sesión y retorna el token de acceso Sanctum. |

---

### 2. Rutas Protegidas (Requieren Bearer Token)

#### Sesión y Perfil
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **POST** | `/auth/logout` | `AuthController@logout` | Cierra la sesión e invalida el token actual. |
| **GET** | `/auth/me` | `AuthController@me` | Obtiene la información del usuario autenticado actualmente. |

#### Usuarios (`/usuarios`)
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/usuarios` | `UsuarioController@index` | Lista todos los usuarios. |
| **POST** | `/usuarios` | `UsuarioController@store` | Crea un nuevo usuario. |
| **GET** | `/usuarios/{usuario}` | `UsuarioController@show` | Detalle de un usuario específico. |
| **PUT/PATCH** | `/usuarios/{usuario}` | `UsuarioController@update` | Actualiza la información de un usuario. |
| **DELETE** | `/usuarios/{usuario}` | `UsuarioController@destroy` | Elimina un usuario (soft-delete). |
| **PATCH** | `/usuarios/{usuario}/restore` | `UsuarioController@restore` | Restaura un usuario que fue eliminado. |

#### Categorías (`/categorias`)
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/categorias` | `CategoriaController@index` | Lista todas las categorías. |
| **POST** | `/categorias` | `CategoriaController@store` | Crea una nueva categoría. |
| **GET** | `/categorias/{categoria}` | `CategoriaController@show` | Detalle de una categoría específica. |
| **PUT/PATCH** | `/categorias/{categoria}` | `CategoriaController@update` | Actualiza una categoría existente. |
| **DELETE** | `/categorias/{categoria}` | `CategoriaController@destroy` | Elimina una categoría (soft-delete). |
| **PATCH** | `/categorias/{categoria}/restore` | `CategoriaController@restore` | Restaura una categoría eliminada. |

#### Productos (`/productos`)
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/productos` | `ProductoController@index` | Lista todos los productos. |
| **POST** | `/productos` | `ProductoController@store` | Crea un nuevo producto. |
| **GET** | `/productos/{producto}` | `ProductoController@show` | Detalle de un producto específico. |
| **PUT/PATCH** | `/productos/{producto}` | `ProductoController@update` | Actualiza un producto existente. |
| **DELETE** | `/productos/{producto}` | `ProductoController@destroy` | Elimina un producto (soft-delete). |
| **PATCH** | `/productos/{producto}/restore` | `ProductoController@restore` | Restaura un producto eliminado. |

#### Pedidos (`/pedidos`)
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/pedidos` | `PedidoController@index` | Lista todos los pedidos. |
| **POST** | `/pedidos` | `PedidoController@store` | Crea un nuevo pedido. |
| **GET** | `/pedidos/{pedido}` | `PedidoController@show` | Detalle de un pedido específico. |
| **PUT/PATCH** | `/pedidos/{pedido}` | `PedidoController@update` | Actualiza la información de un pedido. |
| **DELETE** | `/pedidos/{pedido}` | `PedidoController@destroy` | Elimina un pedido (soft-delete). |
| **PATCH** | `/pedidos/{pedido}/restore` | `PedidoController@restore` | Restaura un pedido eliminado. |

#### Ítems de Pedido (`/items-pedido`)
| Método | Ruta | Controlador y Acción | Descripción |
| :--- | :--- | :--- | :--- |
| **GET** | `/items-pedido` | `ItemPedidoController@index` | Lista todos los ítems de los pedidos. |
| **POST** | `/items-pedido` | `ItemPedidoController@store` | Registra un nuevo ítem para un pedido. |
| **GET** | `/items-pedido/{itemPedido}` | `ItemPedidoController@show` | Detalle de un ítem de pedido específico. |
| **PUT/PATCH** | `/items-pedido/{itemPedido}` | `ItemPedidoController@update` | Actualiza la información de un ítem de pedido. |
| **DELETE** | `/items-pedido/{itemPedido}` | `ItemPedidoController@destroy` | Elimina un ítem de pedido (soft-delete). |
| **PATCH** | `/items-pedido/{itemPedido}/restore` | `ItemPedidoController@restore` | Restaura un ítem de pedido eliminado. |


## Tests

```powershell
php artisan test
```

## Comandos Utiles

Limpiar cache:

```powershell
php artisan optimize:clear
```

Reconstruir autoload:

```powershell
composer dump-autoload -a
```

Resetear base de datos:

```powershell
php artisan migrate:fresh --seed
```
