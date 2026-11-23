# Arquitectura técnica — Terra Moda

## Decisión de plataforma y línea base
El código entregado es una **aplicación web** Laravel con vistas Blade responsivas y actualmente está configurado para **MySQL/MariaDB** (`DB_CONNECTION=mysql`). El Formulario de Cambio GCS-TM-CC-001 recomienda PostgreSQL y arquitectura cliente-servidor, pero también indica que la decisión Android/web y la autorización siguen pendientes. Por eso esta entrega no cambia silenciosamente el motor ni declara aprobado el cambio: la conformidad del equipo y del tutor debe registrarse antes de migrar a PostgreSQL o redefinir el producto.

## Capas
- **Presentación:** Blade, Bootstrap y CSS responsivo.
- **Rutas/controladores:** `routes/web.php` y `app/Http/Controllers`.
- **Persistencia:** MySQL mediante Query Builder y modelos Eloquent iniciales.
- **Reglas críticas:** ventas y transferencias usan `DB::transaction()` y bloqueo `lockForUpdate()` del rollo.

## Modelo de datos
El modelo separa la referencia de tela (`products`) de los rollos físicos (`rolls`). Cada rollo pertenece a una sucursal y puede tener una ubicación física. Las ventas guardan cabecera y detalle, y el detalle apunta al rollo concreto del que se descontó el metraje. Todo cambio por venta o transferencia genera movimientos de inventario.

## Integridad, ubicación y auditoría
- La confirmación de venta se ejecuta dentro de una transacción y bloquea el rollo con `lockForUpdate`; vuelve a comprobar el saldo antes de guardar la venta y rechaza la operación completa si no alcanza el metraje.
- Las ubicaciones usan el código visible `E<estante>-P<posición>`. La reubicación del rollo conserva el origen, destino, usuario, fecha y motivo en `roll_location_movements`.
- La bitácora `audit_logs` conserva el usuario, fecha/hora, acción, entidad y valores anteriores/nuevos de ventas, transferencias y reubicaciones. Los movimientos de inventario se pueden filtrar por rango de fechas.
- La garantía de concurrencia depende del motor y de que la tabla use un motor transaccional (por ejemplo, InnoDB en MySQL). Debe probarse en el entorno real.

## Seguridad y alcance
Las rutas operativas exigen sesión. Las ventas validan sucursal y disponibilidad. Esta versión es un prototipo académico; falta completar permisos granulares, auditoría exhaustiva, gestión de contraseñas y hardening de producción.

## Facturación SIN
Las tablas `fiscal_settings`, `invoices`, `invoice_details` e `invoice_events` reservan la estructura para una integración futura. No se comunica con el SIN ni emite documentos fiscales válidos.
