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

Nota: si no podés entrar con las credenciales de arriba, asegurate de ejecutar los seeders para crear al administrador:

```powershell
php artisan migrate:fresh --seed
# o al menos
php artisan db:seed --class=DatabaseSeeder
```

También podés restablecer la contraseña del admin manualmente desde Tinker:

```powershell
php artisan tinker --execute "\App\Models\Usuario::where('email','admin@tienda.com')->update(['password'=>\Illuminate\Support\Facades\Hash::make('admin1234')]);"
```

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
