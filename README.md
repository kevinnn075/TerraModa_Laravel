# Terra Moda — Plataforma de inventario y ventas

Proyecto web de demostración desarrollado con **Laravel + PHP + MySQL + Blade + Bootstrap** para administrar telas, rollos, metraje, ventas y transferencias de tres sucursales.

> Esta entrega contiene el código fuente del proyecto, migraciones y datos de demostración. No incluye `vendor/`: Composer debe instalar las dependencias en la computadora donde se ejecutará. La integración real con el SIN no está implementada ni certificada; el esquema fiscal queda preparado para una etapa posterior.

## Requisitos
- PHP 8.2 o superior, con extensiones comunes de Laravel (`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`).
- Composer 2.
- MySQL 8 o MariaDB compatible.
- Navegador moderno.
- Internet durante la primera instalación para descargar dependencias y Bootstrap/Google Fonts desde CDN.

## Instalación en Windows (XAMPP + Composer)
Puedes ejecutar `INICIAR_TERRA_MODA.bat` para comprobar PHP/Composer, instalar dependencias si falta `vendor`, preparar `.env` y opcionalmente ejecutar migraciones y datos demo. Antes, crea la base de datos `terra_moda` y verifica las credenciales.

1. Instala XAMPP con PHP 8.2+ y Composer. Inicia **MySQL** desde el panel de XAMPP.
2. Extrae `TerraModa_Laravel.zip` en una carpeta sencilla, por ejemplo `C:\\proyectos\\TerraModa_Laravel`.
3. Abre esa carpeta en Visual Studio Code y abre una terminal dentro de ella.
4. Instala dependencias:
   ```bash
   composer install
   ```
5. El proyecto incluye `.env.example`; cópialo a `.env`:
   ```bash
   copy .env.example .env
   ```
6. Crea la base de datos en phpMyAdmin (http://localhost/phpmyadmin) con el nombre `terra_moda` y cotejamiento `utf8mb4_unicode_ci`.
7. Revisa `.env` y ajusta `DB_USERNAME` y `DB_PASSWORD` a los de tu MySQL local.
8. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
9. Crea las tablas y carga datos de prueba:
   ```bash
   php artisan migrate --seed
   ```
10. Inicia el servidor:
   ```bash
   php artisan serve
   ```
11. Abre http://127.0.0.1:8000.

## Usuario de demostración
- Correo: `admin@terramoda.test`
- Contraseña: `password`

Cambia la contraseña y elimina datos de demostración antes de usarlo con información real.

## Cambios del formulario GCS-TM-CC-001 a CC-004
- **CC-001 (pendiente de aprobación):** se documenta la discrepancia. El proyecto sigue siendo web y MySQL según el código actual; no se migra a PostgreSQL ni se cambia a Android hasta que el equipo y tutor aprueben formalmente la decisión.
- **CC-002:** la venta confirma el saldo dentro de una transacción y bloquea el rollo para impedir sobreventa concurrente.
- **CC-003:** las ubicaciones admiten estante y posición, muestran el formato `E<estante>-P<posición>` y permiten reubicar rollos registrando el historial.
- **CC-004:** se incorpora bitácora de auditoría para ventas, transferencias y reubicaciones, además de filtros de movimientos por rango de fechas.

## Funciones incluidas
- Inicio de sesión local por sesión y contraseña cifrada.
- Panel con productos, rollos, metraje disponible, ventas y resumen por sucursal.
- Catálogo de sucursales, categorías, productos, ubicaciones, rollos y clientes.
- Registro de ventas por metros con cálculo de subtotal, descuento y total.
- Bloqueo del rollo y transacción de base de datos para evitar sobreventa concurrente.
- Descuento automático del metraje vendido y registro de movimiento.
- Transferencias entre sucursales con movimientos de salida y entrada.
- Historial de ventas y transferencias.\n- Reubicación de rollos con historial de ubicación y bitácora consultable.
- Estructura de base de datos adicional para compras, caja, reglas de descuento y documentos fiscales futuros.
- Diseño responsivo en español.

## Alcance y limitaciones de esta versión
- Es una base funcional para demostración académica, no una instalación de producción auditada.
- Los módulos de compras, caja, exportación PDF/Excel, permisos detallados y reglas avanzadas de fidelidad requieren completar sus pantallas y flujos.
- Los formularios CRUD iniciales están orientados a crear/listar registros; aún no incluyen edición/eliminación.
- El usuario de demostración se autentica con un controlador sencillo; antes de producción se recomienda usar el sistema de autenticación estándar de Laravel, autorización con Policies/Gates, protección adicional y revisión de seguridad.
- La integración con facturación electrónica del SIN requiere documentación oficial vigente, credenciales y pruebas; esta entrega solo incorpora tablas de preparación fiscal.
- Los estilos y Bootstrap usan CDN. Para uso sin Internet se debe compilar/instalar los assets localmente.

## Estructura principal
- `app/Http/Controllers`: controladores.
- `app/Http/Middleware`: protección de rutas autenticadas.
- `app/Models`: modelos Eloquent.
- `database/migrations`: estructura MySQL.
- `database/seeders`: sucursales y datos de demostración.
- `resources/views`: vistas Blade.
- `routes/web.php`: rutas web.
- `public/css/app.css`: estilos.
- `docs/`: documentación y diagrama de entidades.

## Problemas frecuentes
- **`composer` no se reconoce:** instala Composer y vuelve a abrir la terminal.
- **Error de conexión MySQL:** confirma que MySQL está iniciado y revisa las credenciales en `.env`.
- **`No application encryption key`:** ejecuta `php artisan key:generate`.
- **No existe la base de datos:** créala antes de `php artisan migrate --seed`.
- **Error de tabla existente durante la migración:** en una instalación de prueba vacía puedes ejecutar `php artisan migrate:fresh --seed`; esto elimina las tablas existentes, úsalo solo si no hay datos importantes.

## Nota académica
La arquitectura y el esquema son una primera versión de desarrollo. Antes de afirmar que está listo para producción, hay que instalar dependencias, ejecutar migraciones, probar ventas/transferencias con distintos usuarios y documentar resultados.
