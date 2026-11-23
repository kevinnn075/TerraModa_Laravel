# Registro de implementación — Formularios GCS-TM-CC-001 a CC-004

## CC-001 — Plataforma y arquitectura
El proyecto recibido contiene una aplicación web Laravel con Blade y configuración MySQL/MariaDB. El formulario deja pendiente la aprobación de la decisión Android/web y recomienda PostgreSQL previa conformidad del equipo y tutor. Por ello se documentó la discrepancia, pero no se cambió el motor de base de datos ni se convirtió el sistema a Android.

## CC-002 — Stock transaccional
La confirmación de venta bloquea el rollo con `lockForUpdate`, valida otra vez la disponibilidad dentro de una transacción y registra venta, detalle, descuento de stock, movimiento y auditoría en la misma transacción. Si no hay metraje suficiente, se lanza un error de validación y la transacción se revierte.

## CC-003 — Ubicación física
Se añadió `locations.position`; el código mostrado se forma como `E<estante>-P<posición>`. Desde el listado de rollos se puede cambiar su ubicación a otra ubicación de la misma sucursal. El sistema guarda el origen, destino, usuario, fecha/hora y motivo en `roll_location_movements`, con pantalla para consultar el historial.

## CC-004 — Auditoría
Se añadió la tabla `audit_logs` y una pantalla Auditoría con filtros por fecha. Se registran ventas confirmadas, transferencias, creación de rollos/productos/ubicaciones y reubicaciones, con valores previos/nuevos cuando corresponden. El listado Movimientos también acepta fechas Desde/Hasta.

## Puesta en marcha y verificación
- Se agregó una migración para `locations.position`, `roll_location_movements` y `audit_logs`.
- Se actualizó el seeder para tener una ubicación demo con código `E3-P1`.
- Se agregó `.env.example` y `INICIAR_TERRA_MODA.bat`.
- Se validó la sintaxis PHP de los archivos del proyecto con `php -l`.
- No se pudo ejecutar una prueba funcional completa dentro de este entorno porque el ZIP no contiene `vendor` y Composer no está disponible aquí. La primera ejecución debe instalar dependencias y correr migraciones en una base de datos local.
