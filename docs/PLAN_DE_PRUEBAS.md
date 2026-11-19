# Plan de pruebas funcionales

| ID | Prueba | Resultado esperado |
|---|---|---|
| P01 | Iniciar sesión con admin@terramoda.test / password | Acceso al dashboard |
| P02 | Registrar producto con código único | Producto guardado |
| P03 | Registrar rollo con metraje inicial | Metraje disponible igual al inicial |
| P04 | Vender una cantidad inferior al saldo | Venta guardada y saldo reducido |
| P05 | Intentar vender más metros que el saldo | Operación rechazada sin cambios |
| P06 | Vender simultáneamente el mismo rollo | El bloqueo evita saldo negativo |
| P07 | Transferir metraje a otra sucursal | Salida y entrada trazables |
| P08 | Acceder a una pantalla sin iniciar sesión | Redirección al login |
| P09 | Revisar dashboard después de vender | Indicadores reflejan la operación |
| P10 | Revisar módulo fiscal | No afirmar emisión SIN hasta integrar y homologar |

Este plan debe ejecutarse en el entorno local y completarse con evidencias (capturas y resultados reales). No se afirma que todas las pruebas hayan sido ejecutadas.
