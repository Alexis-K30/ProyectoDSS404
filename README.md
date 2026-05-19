# Proyecto DSS 404 - Tienda

Este es un proyecto construido con [Laravel](https://laravel.com/) que gestiona el inventario, los usuarios y los pedidos de una tienda de bicicletas. 

A continuación, encontrarás los pasos necesarios para instalar, configurar y levantar el proyecto en tu máquina local.

## 📋 Requisitos Previos

Asegúrate de tener instalados los siguientes programas en tu entorno de desarrollo:

- **PHP** (versión 8.2 o superior recomendada)
- **Composer** (gestor de dependencias de PHP)
- **MySQL / MariaDB** (puedes usar XAMPP, Laragon, o Docker)
- **Git**

---

## 🚀 Guía de Instalación

Sigue estos pasos en orden para levantar el proyecto:

### 1. Clonar el repositorio
Abre tu terminal y clona el proyecto en tu carpeta de desarrollo local:
```bash
git clone <url-del-repositorio>
cd ProyectoDSS404
```

### 2. Instalar las dependencias de PHP
Descarga e instala todos los paquetes necesarios del framework:
```bash
composer install
```

### 3. Configurar el archivo de entorno (.env)
Haz una copia del archivo de ejemplo `.env.example` y renómbralo a `.env`:
```bash
cp .env.example .env
```
*(En Windows puedes simplemente copiar el archivo y renombrarlo manualmente).*

### 4. Generar la Key de la Aplicación
Genera la llave de encriptación de Laravel, la cual se guardará automáticamente en tu archivo `.env`:
```bash
php artisan key:generate
```

---

## 🗄️ Configuración de la Base de Datos (MySQL)

Este proyecto está configurado para utilizar el motor InnoDB de MySQL por defecto, garantizando que todas las relaciones (llaves foráneas) funcionen correctamente.

### 1. Crear la base de datos
Abre tu gestor de base de datos favorito (phpMyAdmin, DBeaver, o consola MySQL) y **crea una base de datos vacía** llamada `laravel` (o el nombre que prefieras).

### 2. Conectar el proyecto a la base de datos
Abre el archivo `.env` que creaste en el paso anterior y asegúrate de que las credenciales de la base de datos coincidan con las de tu entorno local:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel    # Reemplaza por el nombre de tu bd si usaste otro
DB_USERNAME=root       # Usuario de tu base de datos (por defecto 'root' en XAMPP)
DB_PASSWORD=           # Contraseña (suele ir vacía en XAMPP local)
```

### 3. Ejecutar las Migraciones y Seeders
Con la base de datos configurada, crea las tablas y llénalas con los datos de prueba (categorías, 50 productos, usuarios y pedidos). Ejecuta en la terminal:

```bash
php artisan migrate:fresh --seed
```
*Nota: El parámetro `fresh` borra todas las tablas previas si existen, y las vuelve a crear desde cero. El `--seed` inserta los datos de prueba automatizados.*

---

## 🏃‍♂️ Ejecutar el Proyecto

Una vez que todo está instalado y la base de datos está poblada, puedes levantar el servidor de desarrollo integrado de Laravel:

```bash
php artisan serve
```

La aplicación estará disponible en tu navegador ingresando a:
👉 **[http://localhost:8000](http://localhost:8000)**

---

## 🛠️ Comandos Útiles

Si en algún momento necesitas resetear la base de datos o arreglar problemas de caché, puedes usar:

- **Limpiar caché general**: `php artisan optimize:clear`
- **Reconstruir autoloader**: `composer dump-autoload`
- **Resetear base de datos**: `php artisan migrate:fresh --seed`
