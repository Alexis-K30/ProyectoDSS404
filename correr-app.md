# Guía para ejecutar el proyecto Laravel

Parece que intentaste correr el proyecto sin instalar las dependencias. Sigue estos pasos para configurarlo correctamente:

### 1. Instalar dependencias de PHP
Este comando descargará todas las librerías necesarias (carpeta `vendor`):
```bash
composer install
```

### 2. Configurar el archivo de entorno
Crea el archivo `.env` a partir del ejemplo:
```powershell
copy .env.example .env
```

### 3. Generar la clave de la aplicación
```bash
php artisan key:generate
```

### 4. Configurar la Base de Datos
Abre el archivo `.env` y asegúrate de que los datos de conexión sean correctos (nombre de la base de datos, usuario y contraseña) 
por defecto esto va comentado, hay que descomentarlo 
```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Correr las migraciones
Crea las tablas en tu base de datos:
```bash
php artisan migrate
```

### 6. Instalar y compilar dependencias de Frontend
```bash
npm install
npm run dev
```

### 7. Iniciar el servidor
```bash
php artisan serve
```
