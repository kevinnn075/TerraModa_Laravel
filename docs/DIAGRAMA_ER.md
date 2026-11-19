# Diagrama entidad-relación

```mermaid
erDiagram
  BRANCHES ||--o{ USERS : asigna
  BRANCHES ||--o{ LOCATIONS : contiene
  BRANCHES ||--o{ ROLLS : almacena
  CATEGORIES ||--o{ PRODUCTS : clasifica
  PRODUCTS ||--o{ ROLLS : identifica
  LOCATIONS ||--o{ ROLLS : ubica
  ROLLS ||--o{ INVENTORY_MOVEMENTS : registra
  BRANCHES ||--o{ SALES : realiza
  USERS ||--o{ SALES : registra
  CUSTOMERS ||--o{ SALES : compra
  SALES ||--|{ SALE_DETAILS : contiene
  ROLLS ||--o{ SALE_DETAILS : descuenta
  BRANCHES ||--o{ TRANSFERS : origen
  TRANSFERS ||--|{ TRANSFER_DETAILS : incluye
  ROLLS ||--o{ TRANSFER_DETAILS : origen_destino
  SALES ||--o{ INVOICES : referencia
```

## Reglas clave
- `rolls.meters_available` no debe ser negativo.
- La venta descuenta metros del rollo seleccionado.
- La transferencia crea un rollo de destino con el metraje transferido y registra movimientos en ambas sucursales.
- El historial no debe borrarse para corregir operaciones: en una siguiente etapa se implementarán anulaciones y movimientos compensatorios.
