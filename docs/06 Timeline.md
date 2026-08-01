# Timeline

## Definición

La Timeline representa la estructura temporal y evolutiva de un Cultivo.

Es la entidad encargada de organizar el ciclo productivo en etapas, permitiendo planificar, registrar y analizar todo lo ocurrido durante el desarrollo del cultivo.

La Timeline representa la vida completa del cultivo desde su inicio hasta su finalización.

---

# Objetivo

Proporcionar una estructura ordenada donde se pueda:

- Definir las fases esperadas del cultivo.
- Organizar la planificación de actividades.
- Registrar eventos realizados.
- Asociar registros de valores.
- Mantener trazabilidad histórica.
- Generar estadísticas futuras.

---

# Responsabilidades

La Timeline debe:

- Pertenecer a un único Cultivo.
- Gestionar las fases del ciclo productivo.
- Mantener orden cronológico.
- Registrar evolución real del cultivo.
- Comparar planificación vs ejecución.
- Servir como fuente histórica de análisis.

---

# Relación con Cultivo

Cada Cultivo posee una única Timeline.

Ejemplo:

```
Cultivo:

Tropicana Indoor Enero 2026

        |
        |
        ▼

Timeline:

Vegetativo
Floración
Cosecha
Post cultivo
```

---

# Estructura

La Timeline está compuesta por:

```
Timeline

    |
    |
    ├── Fases del Cultivo
    |
    ├── Planificación
    |
    ├── Eventos
    |
    └── Registros
```

---

# Fases

Las fases representan las etapas biológicas/productivas del cultivo.

Inicialmente pueden ser generadas desde una plantilla, pero cada cultivo puede modificar su duración real.

Ejemplo:

```
Timeline:

Vegetativo
Inicio:
01/01/2026

Duración estimada:
28 días

Duración real:
42 días


Floración

Inicio:
12/02/2026

Duración estimada:
63 días

Duración real:
56 días
```

---

# Plantillas de Timeline

Una Timeline puede generarse desde una plantilla predefinida.

Ejemplo:

```
Plantilla:

Indoor Fotoperiódica

Fases:

Vegetativo:
4 semanas

Floración:
9 semanas

Cosecha:
1 semana
```

Al crear un cultivo:

```
Plantilla
    |
    |
    ▼
Timeline del Cultivo
```

La Timeline creada puede modificarse sin afectar la plantilla original.

---

# Información General

| Atributo | Descripción |
|----------|-------------|
| Cultivo | Cultivo asociado. |
| Nombre | Identificación de la Timeline. |
| Fecha inicio | Inicio del ciclo. |
| Fecha fin estimada | Finalización prevista. |
| Fecha fin real | Finalización efectiva. |
| Estado | Planificada, Activa, Finalizada. |

---

# Estados

## Planificada

La Timeline fue creada pero el cultivo todavía no comenzó.

---

## Activa

El cultivo está en desarrollo.

Permite:

- Registrar eventos.
- Cargar mediciones.
- Modificar fases.

---

## Finalizada

El cultivo terminó.

La información queda disponible para análisis histórico.

---

# Eventos dentro de Timeline

Los eventos representan acciones realizadas durante el cultivo.

Ejemplos:

- Trasplante.
- Riego.
- Poda.
- Fertilización.
- Cambio de fotoperiodo.
- Aplicación preventiva.
- Cosecha.

Los eventos deben conservar:

- Fecha.
- Usuario responsable.
- Fase donde ocurrieron.
- Información asociada.

---

# Registros dentro de Timeline

Los registros representan valores medidos o datos cuantificables.

Ejemplos:

Ambientales:

- Temperatura.
- Humedad.
- VPD.

Nutricionales:

- PH.
- EC.
- Runoff.

Productivos:

- Altura.
- Peso.
- Desarrollo.

---

# Alcance de los elementos

Los elementos dentro de una Timeline pueden afectar:

## Cultivo completo

Ejemplo:

Cambio de iluminación.

---

## CultivoVariedad

Ejemplo:

Tratamiento específico para una variedad.

---

## Futuro: Planta individual

Posible extensión futura.

Ejemplo:

Seguimiento de una planta específica.

No forma parte de la primera versión.

---

# Relación con Calendario

El calendario será una representación visual de la Timeline.

No almacena información propia.

Mostrará:

- Fases actuales.
- Actividades planificadas.
- Eventos realizados.
- Registros relevantes.

---

# Estadísticas

La Timeline será una fuente histórica para generar análisis.

Ejemplos:

Por cultivo:

- Duración real.
- Cantidad de eventos.
- Cantidad de registros.
- Incidentes.
- Evolución ambiental.

Por variedad:

- Comportamiento histórico.
- Relación manejo/resultado.

Por sala:

- Rendimiento según condiciones.

---

# Reglas de Negocio

- Un Cultivo posee una única Timeline.
- Una Timeline pertenece a un único Cultivo.
- Las fases pueden modificarse durante el cultivo.
- La duración estimada y la duración real deben conservarse.
- La información histórica no debe perderse.
- Una Timeline finalizada debe permanecer disponible para análisis.
- Las plantillas no deben modificarse cuando ya fueron utilizadas por cultivos existentes.

---

# Estado

🟡 En revisión (Versión 1)