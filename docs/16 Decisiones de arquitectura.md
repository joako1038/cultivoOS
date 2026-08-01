# Decisiones de Arquitectura

Este documento registra las decisiones importantes tomadas durante el diseño del sistema.

El objetivo es mantener el contexto del proyecto y evitar modificar conceptos fundamentales sin analizar el impacto.

---

# DA-001 - La Sala representa infraestructura permanente

## Fecha

2026-07-30

## Decisión

La entidad Sala representa el espacio físico donde se desarrollan los cultivos.

La Sala contiene características propias del ambiente e infraestructura, independientemente de los cultivos realizados.

---

## Motivo

Una misma sala puede contener múltiples cultivos a lo largo del tiempo.

Ejemplo:

Sala Flora 1:

- Cultivo Enero 2026.
- Cultivo Mayo 2026.
- Cultivo Septiembre 2026.

Los parámetros propios del ciclo productivo no deben almacenarse en la Sala.

---

## Estado

Aceptada.


---

# DA-002 - El Cultivo es la unidad principal de seguimiento

## Fecha

2026-07-30

## Decisión

El Cultivo representa un ciclo productivo completo y será la unidad principal de trazabilidad.

---

## Motivo

Toda actividad realizada durante un ciclo pertenece al Cultivo:

- Eventos.
- Registros.
- Mediciones.
- Observaciones.
- Producción final.

Esto permite reconstruir la historia completa de un ciclo.

---

## Estado

Aceptada.


---

# DA-003 - La genética se separa del cultivo mediante CatálogoVariedad

## Fecha

2026-07-30

## Decisión

La información general de una variedad genética se almacena separada del cultivo mediante un Catálogo de Variedades.

La participación de una variedad dentro de un cultivo se representa mediante CultivoVariedad.

---

## Motivo

Una misma genética puede utilizarse en diferentes cultivos.

Ejemplo:

Tropicana WFC:

- Cultivo Enero 2026.
- Cultivo Junio 2026.
- Cultivo Marzo 2027.

La información genética no debe duplicarse.

---

## Estado

Aceptada.


---

# DA-004 - CultivoVariedad representa la utilización real de una genética

## Fecha

2026-07-30

## Decisión

Se crea una entidad intermedia entre Cultivo y CatálogoVariedad.

---

## Motivo

La misma variedad puede tener comportamientos diferentes según:

- Sala utilizada.
- Parámetros de cultivo.
- Manejo realizado.
- Condiciones ambientales.

Ejemplo:

Tropicana WFC en Sala A puede tener resultados diferentes que en Sala B.

---

## Estado

Aceptada.


---

# DA-005 - La Timeline será la fuente histórica del sistema

## Fecha

2026-07-30

## Decisión

Los eventos y registros se almacenarán dentro de una línea temporal asociada al Cultivo.

El calendario será una representación visual de esta información.

---

## Motivo

El sistema no debe funcionar únicamente como un calendario de tareas.

Debe permitir responder:

- Qué ocurrió.
- Cuándo ocurrió.
- Quién lo realizó.
- Sobre qué entidad tuvo efecto.
- Qué resultado produjo.

---

## Estado

Aceptada.


---

# DA-006 - El calendario no será una entidad principal

## Fecha

2026-07-30

## Decisión

El calendario será una vista generada a partir de eventos existentes.

---

## Motivo

Una tarea/evento puede existir aunque no sea mostrada en calendario.

La información real pertenece al evento, no a la representación visual.

---

## Estado

Aceptada.


---

# DA-007 - Organización como entidad raíz del sistema

## Fecha

2026-07-30

## Decisión

La entidad principal del sistema será Organización.

Una Organización puede representar:

- ONG.
- Autocultivador.
- Futuros tipos.

---

## Motivo

Permite soportar diferentes formas de uso sin crear sistemas separados.

---

## Estado

Aceptada.


---

# DA-008 - Usuarios separados de la propiedad de los recursos

## Fecha

2026-07-30

## Decisión

Los usuarios representan personas con acceso al sistema, pero no son dueños directos de salas o cultivos.

El acceso estará determinado por roles.

---

## Motivo

En una ONG varias personas pueden intervenir sobre un mismo cultivo.

Ejemplo:

- Un usuario configura.
- Otro registra tareas.
- Otro realiza mediciones.

La trazabilidad requiere identificar quién realizó cada acción.

---

## Estado

Aceptada.


---

# DA-009 - Roles antes que permisos granulares

## Fecha

2026-07-30

## Decisión

La primera versión utilizará roles simples.

No se implementará inicialmente un sistema complejo de permisos.

---

## Motivo

La prioridad es validar el funcionamiento del dominio.

Un sistema avanzado de permisos puede agregarse cuando existan necesidades reales.

---

## Estado

Pendiente de evolución.


---

# DA-010 - No mezclar normativa con entidades productivas

## Fecha

2026-07-30

## Decisión

Las reglas regulatorias no formarán parte directa de Sala, Cultivo o Variedad.

---

## Motivo

Las normativas pueden cambiar.

Ejemplo:

- Cantidad máxima de plantas.
- Restricciones según tipo de organización.

Estas reglas deben poder modificarse sin alterar el modelo productivo.

---

## Estado

Aceptada.


---

# Próximas decisiones pendientes

- Modelo definitivo de Timeline.
- Tipos de eventos.
- Modelo de registros de valores.
- Sistema de notificaciones.
- Modelo de tareas programadas.
- Definición de permisos avanzados.
- Modelo de sensores y mediciones automáticas.

# DA-012 - Los registros son adaptativos al cultivo

## Decisión

Los registros no tendrán una estructura fija universal.

Cada cultivo definirá qué variables necesita monitorear según sus características productivas.

Ejemplos:

- Sistema de cultivo.
- Método de riego.
- Nivel de automatización.
- Objetivos de seguimiento.

---

## Motivo

No todos los cultivos requieren la misma información.

Un cultivo hidropónico puede requerir:

- PH.
- EC.
- Litros consumidos.
- Temperatura solución.

Mientras que un cultivo en suelo puede requerir:

- Humedad del sustrato.
- Litros aplicados.
- Observaciones visuales.

---

## Estado

Aceptada.

---

# DA-013 - La configuración pertenece al cultivo

## Decisión

Cada Cultivo posee una ConfiguraciónCultivo propia.

Esta configuración define:

- Cómo funciona el cultivo.
- Qué variables monitorear.
- Frecuencia de registros.
- Particularidades del sistema.

---

## Motivo

El cultivo es la unidad donde existen las particularidades productivas.

Evita crear modelos genéricos demasiado complejos.

---

## Estado

Aceptada.

---

# DA-014 - Los registros representan estado, los eventos representan acciones

## Decisión

Se separan dos conceptos:

Registro:

Representa un valor, medición o estado del cultivo.

Ejemplo:

- Temperatura.
- Humedad.
- PH.
- Litros consumidos.

Evento:

Representa una acción realizada.

Ejemplo:

- Preparación de solución.
- Poda.
- Trasplante.
- Cambio de configuración.

---

## Motivo

Permite diferenciar:

"Qué estaba pasando"

de

"Qué hizo una persona o sistema".

---

## Estado

Aceptada.

---

# DA-015 - La frecuencia de registros es configurable

## Decisión

Cada cultivo puede definir la cantidad de registros esperados por jornada.

Ejemplos:

Seguimiento básico:

1 registro diario.

Seguimiento intensivo:

3 registros diarios.

---

## Motivo

Los requerimientos de monitoreo dependen del nivel de control requerido.

---

## Estado

Aceptada.

---

# DA-016 - Los eventos no son obligatorios diariamente

## Decisión

Un día del cultivo puede existir sin eventos.

Sin embargo, durante la etapa activa del cultivo deben existir registros según la configuración definida.

---

## Motivo

El estado del cultivo debe mantenerse aunque no existan intervenciones.

---

## Estado

Aceptada.

---

# DA-017 - La configuración puede evolucionar durante el cultivo

## Decisión

La ConfiguraciónCultivo puede modificarse durante el ciclo.

Ejemplos:

- Agregar sensores.
- Cambiar sistema de riego.
- Incorporar nuevas variables.

Los cambios no deben modificar registros históricos.

---

## Motivo

Los cultivos reales evolucionan.

---

## Estado

Pendiente de implementación histórica.
## Decisión

Los Eventos podrán asociarse opcionalmente a una Planificación del mismo Cultivo.

La asociación será una referencia simple.

## Motivo

Permite vincular acciones realizadas con actividades previstas sin introducir una estructura compleja de seguimiento.

## Alcance V1

Incluye:

- Evento asociado a una planificación.
- Eventos independientes.

No incluye:

- Árbol de ejecuciones.
- Múltiples niveles de planificación.
- Dependencias entre eventos.

## Estado

Aceptada para versión inicial.


# DA-019 - Planta como entidad independiente

## Decisión

La Planta no pertenece directamente a un único Cultivo.

Representa un individuo con historia propia.

## Motivo

Una planta puede atravesar diferentes etapas productivas y participar en diferentes ciclos.

## Estado

Aceptada para revisión V1.


# Decisiones de Arquitectura

## ADR-001 — Identificadores

- Todas las entidades principales utilizan UUID como clave primaria.
- Se adopta `gen_random_uuid()` de PostgreSQL para su generación.

---

## ADR-002 — Auditoría

Todas las entidades de negocio incorporan:

- `created_at`
- `updated_at`

Las entidades que lo requieran podrán incorporar además:

- `deleted_at` (Soft Delete)

---

## ADR-003 — Convención de nombres

Se adopta `snake_case` para toda la base de datos.

Ejemplos:

- `catalogo_variedad`
- `cultivo_variedad`
- `estado_evento`
- `tipo_evento`
- `estado_planta`

Las claves foráneas utilizan siempre el formato:

```
<tabla>_id
```

Ejemplo:

```
cultivo_id
usuario_id
organizacion_id
```

---

## ADR-004 — Catálogos

Los valores estáticos del sistema se representan mediante tablas catálogo.

Ejemplos:

- tipo_sala
- estado_sala
- estado_evento
- tipo_evento
- estado_planta
- rol

Las entidades de negocio referencian dichos catálogos mediante claves foráneas.

---

## ADR-005 — Relaciones

Las claves foráneas siempre se almacenan en la entidad dependiente (hija).

Ejemplo:

```
Cultivo
    └── sala_id
```

y nunca:

```
Sala
    └── cultivo_id
```

Esta convención se mantiene para todo el modelo.

---

## ADR-006 — Separación entre infraestructura y operación

Se distinguen claramente tres niveles:

Infraestructura

- Organización
- Sala

Operación

- Cultivo
- Timeline
- Evento
- Registro

Producción

- Planta
- CultivoVariedad
- CatálogoVariedad

Cada nivel posee responsabilidades independientes.

---

## ADR-007 — La Planta es la unidad mínima de trazabilidad

El sistema considera a la Planta como la unidad mínima trazable.

Toda información histórica puede asociarse a:

- un Cultivo
- una Planta
- o ambos

Esto permite registrar eventos generales del cultivo y eventos específicos sobre una planta individual.

---

## ADR-008 — CultivoVariedad es una entidad de negocio

`cultivo_variedad` no se considera una simple tabla puente.

Representa la participación de una variedad específica dentro de un cultivo determinado.

Una misma variedad puede participar en múltiples cultivos y un cultivo puede contener múltiples variedades.

La entidad podrá almacenar información propia, por ejemplo:

- cantidad planificada
- cantidad real
- observaciones
- métricas
- rendimiento

---

## ADR-009 — La Planta pertenece a una variedad dentro de un cultivo

La Planta no referencia únicamente al Cultivo.

Cada Planta pertenece a una instancia específica de `cultivo_variedad`.

```
CatalogoVariedad
        │
        ▼
CultivoVariedad
        │
        ▼
     Planta
```

Esto permite diferenciar correctamente plantas de distintas variedades que conviven dentro de un mismo cultivo.

Opcionalmente la entidad Planta puede conservar también `cultivo_id` para optimizar consultas frecuentes, manteniendo la consistencia mediante reglas de negocio.

---

## ADR-010 — Timeline como representación temporal

El Timeline representa la planificación temporal completa de un cultivo.

No almacena únicamente eventos.

Representa la evolución cronológica del cultivo y sirve como eje para:

- fases
- eventos
- planificaciones
- registros
- estadísticas futuras

---

## ADR-011 — Configuración desacoplada del Cultivo

Las características operativas del cultivo no se almacenan directamente en la entidad Cultivo.

Se concentran en ConfiguraciónCultivo.

Ejemplos:

- tipo de riego
- cantidad de registros diarios
- uso de CO₂
- automatizaciones
- configuración ambiental

Esto permite que distintos cultivos compartan el mismo modelo sin incorporar campos específicos para cada modalidad de producción.