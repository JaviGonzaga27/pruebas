# Sistema de Taller Mecánico

Sistema de gestión para talleres automotrices desarrollado con PHP, MySQL y Bootstrap.

## Características principales
- Gestión de clientes y vehículos
- Control de inventario
- Gestión de compras y ventas
- Sistema de roles de usuario
- Autenticación segura

## Requisitos
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- Módulos PHP: PDO, MySQL

## Instalación
1. Clonar el repositorio
2. Importar la base de datos `database/schema.sql`
3. Configurar conexión en `conexion.php`
4. Configurar permisos de carpetas
5. Acceder a `http://localhost/mecanica2`

## Estructura del proyecto
- `/users` - Gestión de clientes y vehículos
- `/inventario` - Control de inventario
- `/admin` - Panel administrativo
- `/assets` - Recursos frontend

## Seguridad
- Protección contra CSRF
- Prevención de SQL Injection
- Control de sesiones
- Roles de usuario

## Licencia
MIT License