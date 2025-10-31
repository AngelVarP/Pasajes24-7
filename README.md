# Pasajes24-7

Pasajes24-7 es una aplicación web desarrollada con el framework Laravel, diseñada para gestionar la búsqueda y reserva de pasajes de autobús. El sistema ofrece una interfaz pública para que los clientes busquen viajes y seleccionen asientos, así como un panel de administración protegido para gestionar la logística del negocio.

---

## Características Principales

El proyecto se divide en dos áreas principales: el portal del cliente y el panel de administración.

### Portal Público (Cliente)

Basado en las rutas web definidas, los usuarios pueden:

* **Página de Inicio**: Ver la interfaz principal de búsqueda.
* **Búsqueda de Viajes**: Buscar viajes disponibles especificando origen, destino y (presumiblemente) fecha.
* **Búsqueda de Ciudades**: Obtener dinámicamente una lista de ciudades.
* **Selección de Asientos**: Visualizar y seleccionar asientos disponibles para un viaje específico.
* **Proceso de Reserva**:
    * Ingresar los datos de los pasajeros.
    * Procesar y almacenar la reserva.
    * Ver una página de confirmación de la reserva exitosa.

### Panel de Administración

El sistema cuenta con un panel de administración (`/admin`) protegido por autenticación. Los administradores pueden:

* **Dashboard**: Ver un panel de control principal.
* **Gestión de Viajes (CRUD)**: Crear, ver, editar, actualizar y cancelar viajes. También incluye una función para actualizar manualmente los estados de los viajes.
* **Gestión de Rutas (CRUD)**: Administrar las rutas de viaje (orígenes y destinos).
* **Gestión de Ciudades (CRUD)**: Administrar las ciudades disponibles en el sistema.
* **Gestión de Empresas (CRUD)**: Administrar las empresas de transporte asociadas.
* **Reporte de Reservas**: Ver un índice de todas las reservas realizadas.

---

## Tecnologías Utilizadas

Este proyecto utiliza una pila de tecnologías moderna basada en PHP y JavaScript.

### Backend (Stack de PHP)

Información extraída del archivo `composer.json`:

* **PHP**: `^8.2`
* **Framework**: Laravel `^12.0`
* **Autenticación API/Móvil**: Laravel Sanctum `^4.2`
* **Herramientas de desarrollo**:
    * Laravel Sail
    * Laravel Pint (estilo de código)
    * Pail (logs)
    * PHPUnit (pruebas)

### Frontend (Stack de JavaScript)

Información extraída del archivo `package.json`:

* **Bundler/Compilador**: Vite `^7.0.7`
* **Framework CSS**:
    * TailwindCSS `^3.4.1`
    * Bootstrap `^5.3.8` (ambos están instalados)
* **Framework de JavaScript**: Alpine.js `^3.15.1`
* **Cliente HTTP**: Axios `^1.11.0`
* **Utilidades de desarrollo**: Concurrently (para ejecutar múltiples scripts)

---

## Instalación y Configuración

El archivo `composer.json` define scripts para facilitar la configuración del entorno.

### 1. Configuración Inicial (Script `setup`)

Para una instalación completa desde cero, el proyecto incluye un script `setup` que automatiza los siguientes pasos:

1.  `composer install` (Instala dependencias de PHP)
2.  `@php -r "file_exists('.env') || copy('.env.example', '.env');"` (Crea el archivo `.env`)
3.  `@php artisan key:generate` (Genera la clave de la aplicación)
4.  `@php artisan migrate --force` (Ejecuta las migraciones de la base de datos)
5.  `npm install` (Instala dependencias de Node.js)
6.  `npm run build` (Compila los assets de frontend)

### 2. Ejecutar en Entorno de Desarrollo (Script `dev`)

Para levantar el entorno de desarrollo, el script `dev` utiliza `concurrently` para ejecutar todos los servicios necesarios simultáneamente:

```bash
composer run dev

