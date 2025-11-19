# 📦 Inventory-Tec — Sistema de Gestión y Asignación de Equipos

Inventory-Tec es un sistema web desarrollado en **Laravel 12** para la **gestión, control y asignación de dispositivos** dentro de una organización.  
Permite administrar empleados, empresas, tipos de dispositivos y manejar asignaciones con historial, devoluciones y reportes en PDF.

## 🚀 Características principales

- Gestión de empresas  
- Gestión de empleados  
- Gestión de dispositivos con especificaciones dinámicas  
- Control de tipos de dispositivos  
- Asignación de dispositivos a empleados  
- Registro de devoluciones  
- Historial completo por empleado y dispositivo  
- Generación de comprobantes en PDF  
- Filtros por empresa, estado y búsqueda inteligente  
- AJAX para cargar empleados y dispositivos disponibles  
- Interfaz moderna con TailwindCSS  
- Autenticación con Laravel Breeze / Auth  

## 🛠️ Tecnologías utilizadas

| Componente | Tecnología |
|-----------|------------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade + TailwindCSS |
| Base de datos | MySQL / MariaDB |
| ORM | Eloquent |
| PDF | DOMPDF / Snappy |
| Autenticación | Laravel Breeze / Auth |
| AJAX | Fetch API |

## 📂 Estructura del proyecto

```
inventory-tec/
 ├── app/
 │   ├── Models/
 │   ├── Http/Controllers/
 ├── resources/views/
 ├── database/migrations/
 ├── routes/web.php
 └── README.md
```

## ⚙️ Requisitos

- PHP ≥ 8.2  
- Composer ≥ 2.5  
- Node.js ≥ 18  
- MySQL ≥ 5.7  
- Extensiones PHP necesarias  

## 📥 Instalación

### 1. Clonar repositorio
```bash
git clone https://github.com/ProyectosSoft/inventory-tec.git
cd inventory-tec
```

### 2. Instalar dependencias PHP
```bash
composer install
```

### 3. Dependencias Javascript
```bash
npm install
npm run build
```

### 4. Configurar entorno
```bash
cp .env.example .env
php artisan key:generate
```

Editar .env base de datos.

### 5. Migraciones
```bash
php artisan migrate
```

### 6. Ejecutar proyecto
```bash
php artisan serve
```

## 🧾 Módulo de Asignaciones

Incluye: creación, historial, devoluciones, filtros y PDF.

## 🧑‍💻 Autor

**Ivan Gómez — ProyectosSoft**
