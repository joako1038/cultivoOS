# Cultivo

## Definición

Un Cultivo representa un ciclo productivo desarrollado dentro de una Sala.

Es la unidad principal de seguimiento operativo e histórico del sistema.

Todo lo que ocurre durante ese ciclo queda registrado dentro del Cultivo.

---

## Responsabilidades

- Gestionar un ciclo productivo.
- Definir la configuración utilizada.
- Asociar las variedades utilizadas.
- Registrar eventos y mediciones.
- Mantener la trazabilidad completa.
- Generar información histórica para análisis.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre identificatorio. |
| Código | Código interno. |
| Sala | Espacio físico donde se desarrolla. |
| Responsable | Usuario responsable. |
| Fecha Inicio | Inicio del ciclo. |
| Fecha Fin | Finalización del ciclo. |
| Estado | Planificado, Activo, Finalizado, Archivado. |

---

# Configuración del Cultivo

Describe cómo fue realizado el ciclo productivo.

Ejemplos:

- Tipo de cultivo.
- Sistema de riego.
- Sustrato.
- Método de cultivo.
- Uso de runoff.
- Objetivos productivos.

Estos datos pertenecen al Cultivo porque pueden variar entre ciclos realizados en la misma Sala.

---

# Variedades utilizadas

Un Cultivo puede estar compuesto por una o varias variedades.

La relación se realiza mediante la entidad:

## CultivoVariedad

Representa la participación de una variedad dentro de un cultivo específico.

Ejemplo:

Cultivo Enero 2026:

- Tropicana WFC
  - 24 plantas
  - Estado: Finalizado

- Gorilla Glue
  - 18 plantas
  - Estado: Finalizado

---

# Timeline

El Cultivo contiene una línea temporal donde se registran:

- Eventos.
- Mediciones.
- Registros.
- Notas.
- Incidentes.
- Archivos.

---

# Producción

Al finalizar el Cultivo pueden registrarse:

- Producción total.
- Producción por variedad.
- Calidad.
- Observaciones finales.
- Resultados obtenidos.

---

# Relaciones

Un Cultivo:

Pertenece a:

- Una Sala.

Contiene:

- Una o varias CultivoVariedad.
- Una Timeline.
- Resultados productivos.

Relaciona con:

- Catálogo de Variedades.

---

# Reglas de Negocio

- Un Cultivo pertenece únicamente a una Sala.
- Una Sala puede tener múltiples Cultivos históricos.
- Un Cultivo puede tener una o varias variedades.
- Una variedad puede participar en múltiples Cultivos.
- Los eventos pueden afectar al Cultivo completo o a una variedad específica.
- Los resultados obtenidos permiten generar estadísticas futuras.

---

## Estado

🟢 Estable (Versión 1.1)

----

# Relación con Plantas

Un Cultivo puede contener múltiples Plantas durante su desarrollo.

La relación entre Cultivo y Planta representa la participación de una planta dentro de un ciclo productivo.

Una misma Planta puede participar en diferentes Cultivos a lo largo de su ciclo de vida.

---

## Ejemplo

Planta P001:

```
Cultivo:
Lote Esquejes Julio

Fase:
Esqueje


↓

Cultivo:
Producción Indoor Agosto

Fase:
Vegetativo


↓

Cultivo:
Producción Indoor Agosto

Fase:
Floración
```

---

## Consideraciones

El Cultivo no almacena el estado histórico individual de cada planta.

Los cambios particulares de una planta deben registrarse mediante Eventos asociados a la planta.

Ejemplos:

- Cambio de fase.
- Trasplante.
- Baja.
- Cosecha individual.
- Observaciones particulares.
