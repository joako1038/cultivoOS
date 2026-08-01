# Reglas de Negocio

---

# Cultivo

## RN-001

Todo Cultivo debe poseer una ConfiguraciónCultivo.

---

## RN-002

Todo Cultivo activo debe poseer una Timeline.

---

## RN-003

Un Cultivo puede tener múltiples variedades asociadas.

---

# Timeline

## RN-004

Cada Cultivo posee una única Timeline principal.

---

## RN-005

La Timeline debe conservar la planificación inicial y la ejecución real.

---

## RN-006

Las fases pueden modificar su duración sin perder la duración originalmente planificada.

---

# Fases

## RN-007

Toda fase pertenece a una única Timeline.

---

## RN-008

Una fase debe conservar:

- Fecha estimada.
- Fecha real.
- Duración estimada.
- Duración real.

---

# Registros

## RN-009

Los registros dependen de la configuración activa del cultivo.

---

## RN-010

Un cultivo puede requerir diferentes tipos de registros según su configuración.

Ejemplo:

Hidroponía:

- PH.
- EC.
- Litros consumidos.

Suelo:

- Humedad.
- Riego aplicado.

---

## RN-011

Durante una jornada activa del cultivo debe existir al menos un registro cuando la configuración lo requiera.

---

## RN-012

Los registros históricos no deben eliminarse cuando cambia la configuración.

---

# Eventos

## RN-013

Los eventos representan acciones realizadas.

---

## RN-014

Los eventos pueden existir sin registros adicionales.

---

## RN-015

Un evento debe conservar:

- Fecha.
- Usuario responsable.
- Tipo.
- Contexto dentro del cultivo.

---

# ConfiguraciónCultivo

## RN-016

La configuración define el comportamiento operativo del cultivo.

---

## RN-017

La configuración puede modificarse durante el ciclo productivo.

---

## RN-018

Los cambios de configuración deben conservar trazabilidad.

---

# Historial

## RN-019

La información generada durante un cultivo debe permitir reconstruir su evolución completa.

---

## RN-020

Los datos históricos serán utilizados para análisis y estadísticas futuras.

---


## RN - Asociación Planificación Evento

Un Evento puede tener una Planificación asociada.

Un Evento también puede existir sin Planificación.

La Planificación asociada debe pertenecer al mismo Cultivo.

La asociación no modifica el historial del Evento.

## ADR-013 — Separación entre Planificación, Evento y Registro

El sistema distingue tres conceptos fundamentales:

### Planificación

Representa una acción prevista para el futuro.

No implica ejecución.

Una planificación puede generar uno o más eventos.

---

### Evento

Representa una acción efectivamente realizada.

Puede modificar el estado del sistema y constituye la fuente de trazabilidad operativa.

Un evento puede estar asociado a una planificación o ser ejecutado de forma espontánea.

---

### Registro

Representa información observada.

No modifica el estado del sistema.

Su objetivo es documentar las condiciones del cultivo en un momento determinado.

Los registros pueden generarse periódicamente según la configuración del cultivo.

--

## Bitácora

Es la representación cronológica completa del cultivo.

No constituye una entidad independiente de persistencia.

Se construye a partir de:

- Timeline
- Planificaciones
- Eventos
- Registros

Su finalidad es ofrecer una visión histórica, ordenada y trazable de la evolución del cultivo.