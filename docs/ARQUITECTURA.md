# Arquitectura técnica — Terra Moda

## Capas
- **Presentación:** Blade, Bootstrap y CSS responsivo.
- **Rutas/controladores:** `routes/web.php` y `app/Http/Controllers`.
- **Persistencia:** MySQL mediante Query Builder y modelos Eloquent iniciales.
- **Reglas críticas:** ventas y transferencias usan `DB::transaction()` y bloqueo `lockForUpdate()` del rollo.

## Modelo de datos
El modelo separa la referencia de tela (`products`) de los rollos físicos (`rolls`). Cada rollo pertenece a una sucursal y puede tener una ubicación física. Las ventas guardan cabecera y detalle, y el detalle apunta al rollo concreto del que se descontó el metraje. Todo cambio por venta o transferencia genera movimientos de inventario.

## Seguridad y alcance
Las rutas operativas exigen sesión. Las ventas validan sucursal y disponibilidad. Esta versión es un prototipo académico; falta completar permisos granulares, auditoría exhaustiva, gestión de contraseñas y hardening de producción.

## Facturación SIN
Las tablas `fiscal_settings`, `invoices`, `invoice_details` e `invoice_events` reservan la estructura para una integración futura. No se comunica con el SIN ni emite documentos fiscales válidos.
