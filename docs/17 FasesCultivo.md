# FaseCultivo

## Definición

FaseCultivo representa una etapa dentro de la Timeline de un Cultivo.

Cada fase define un período temporal con un objetivo productivo determinado y permite organizar la planificación, ejecución y análisis del ciclo.

Una fase pertenece exclusivamente a una Timeline.

---

# Objetivo

Permitir dividir un cultivo en etapas manejables y comparables.

Ejemplos:

- Vegetativo.
- Floración.
- Cosecha.
- Post cultivo.

---

# Responsabilidades

Una FaseCultivo debe:

- Definir una etapa dentro del ciclo productivo.
- Mantener fechas planificadas y reales.
- Ordenar cronológicamente el desarrollo del cultivo.
- Agrupar actividades realizadas durante esa etapa.
- Permitir análisis históricos por etapa.

---

# Relación

Una FaseCultivo:

Pertenece a:

- Una Timeline.

Puede contener:

- Planificaciones.
- Eventos.
- Registros.
- Observaciones.

---

# Modelo conceptual

```
Cultivo

   |
   |
   └── Timeline

          |
          |
          ├── Fase Vegetativa
          |
          ├── Fase Floración
          |
          └── Fase Cosecha
```

---

# Tipos de Fase

Versión inicial:

| Tipo | Descripción |
|------|-------------|
| Vegetativo | Desarrollo estructural de la planta. |
| Floración | Desarrollo floral y maduración. |
| Cosecha | Preparación y ejecución de cosecha. |
| Post cultivo | Cierre y análisis del ciclo. |
| Otro | Fases personalizadas. |

---

# Información General

| Atributo | Descripción |
|----------|-------------|
| Timeline | Timeline a la que pertenece. |
| Nombre | Nombre visible de la fase. |
| Tipo | Tipo de fase. |
| Orden | Posición dentro de la Timeline. |
| Estado | Pendiente, Activa, Finalizada. |

---

# Información Temporal

La fase debe conservar tanto la planificación como la ejecución real.

| Atributo | Descripción |
|----------|-------------|
| Fecha inicio estimada | Inicio esperado. |
| Fecha fin estimada | Finalización esperada. |
| Fecha inicio real | Inicio efectivo. |
| Fecha fin real | Finalización efectiva. |
| Duración estimada | Cantidad de días prevista. |
| Duración real | Cantidad de días reales. |

---

# Ejemplo

## Fase Vegetativa

```
Tipo:
Vegetativo

Inicio estimado:
01/01/2026

Fin estimado:
28/01/2026

Inicio real:
01/01/2026

Fin real:
10/02/2026

Duración estimada:
28 días

Duración real:
40 días
```

---

# Estados

## Pendiente

La fase fue creada pero todavía no comenzó.

---

## Activa

La fase se encuentra actualmente en desarrollo.

Puede recibir:

- Eventos.
- Registros.
- Actividades planificadas.

---

## Finalizada

La fase terminó.

Sus datos quedan disponibles para análisis.

---

# Duración de Fases

Las fases tienen una duración inicial definida por una plantilla.

Ejemplo:

```
Plantilla Indoor:

Vegetativo:
28 días

Floración:
63 días
```

Sin embargo, cada cultivo puede modificar su duración.

Ejemplo:

```
Cultivo real:

Vegetativo:
40 días

Floración:
56 días
```

La modificación de duración no afecta la plantilla original.

---

# Relación con Eventos

Los eventos ocurridos dentro de un período pueden asociarse a la fase correspondiente.

Ejemplo:

```
Fase:
Vegetativo

Eventos:

- Trasplante.
- Poda.
- Entrenamiento.
- Cambio de iluminación.
```

---

# Relación con Registros

Los registros realizados durante la fase permiten analizar su evolución.

Ejemplo:

```
Fase:
Floración

Registros:

Semana 1:
VPD 1.1

Semana 4:
VPD 1.3

Semana 7:
VPD 1.2
```

---

# Relación con Estadísticas

Las fases permiten generar análisis como:

- Duración promedio de vegetativo.
- Duración promedio de floración.
- Problemas frecuentes por etapa.
- Condiciones ambientales por etapa.
- Relación entre duración y producción.

---

# Reglas de Negocio

- Una FaseCultivo pertenece a una única Timeline.
- Una Timeline puede tener múltiples fases.
- Las fases tienen un orden definido.
- La duración estimada y real deben conservarse.
- Una fase modificada no debe alterar la plantilla original.
- Los eventos y registros pueden asociarse a una fase.
- La finalización de una fase no elimina información histórica.

---

# Estado

🟡 En revisión (Versión 1)