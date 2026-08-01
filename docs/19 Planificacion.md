# Planificación

## Definición

La Planificación representa las acciones, controles o actividades esperadas durante el desarrollo de un Cultivo.

Define qué debería ocurrir, cuándo debería ocurrir y bajo qué condiciones.

La Planificación pertenece a la Timeline del Cultivo.

---

# Objetivo

Permitir organizar anticipadamente:

- Actividades productivas.
- Mediciones esperadas.
- Manejos.
- Controles.
- Intervenciones.

---

# Relación

Una Planificación pertenece a:

- Una Timeline.

Puede estar asociada a:

- Una FaseCultivo.
- Una variedad específica dentro del cultivo.
- Un responsable.

Puede generar:

- Eventos reales.
- Registros esperados.

---

# Modelo conceptual

```
Cultivo

 |
 |
Timeline

 |
 |
Planificación

 |
 |
Evento realizado
```

---

# Diferencia entre Planificación y Evento

## Planificación

Representa intención.

Ejemplo:

```
Realizar poda
Fecha prevista:
20/02
```

---

## Evento

Representa ejecución.

Ejemplo:

```
Poda realizada
Fecha:
23/02
```

---

# Estados

## Pendiente

La actividad todavía no fue realizada.

---

## Realizada

Existe un evento asociado.

---

## Cancelada

La actividad no será realizada.

---

## Retrasada

Fue realizada fuera de la fecha prevista.

---

# Información General

| Atributo | Descripción |
|-|-|
| Timeline | Cultivo asociado |
| Fase | Etapa donde aplica |
| Tipo | Tipo de actividad |
| Fecha prevista | Momento esperado |
| Responsable | Usuario asignado |
| Estado | Estado actual |
| Descripción | Detalle |

---

# Tipos de Planificación

Ejemplos:

## Actividades

- Poda.
- Trasplante.
- Fertilización.
- Limpieza.

---

## Seguimiento

- Medición ambiental.
- Control PH.
- Control EC.

---

## Procesos

- Inicio floración.
- Cambio fotoperiodo.
- Cosecha.

---

# Relación con Fases

La planificación normalmente pertenece a una fase.

Ejemplo:

```
Fase:

Floración semana 3


Planificaciones:

- Limpieza inferior
- Ajuste nutricional
- Medición EC
```

---

# Relación con Variedades

Una planificación puede afectar:

Todo el cultivo:

Ejemplo:

Cambio de fotoperiodo.

O una variedad:

Ejemplo:

Tratamiento específico Tropicana.
```

---

# Relación con Calendario

El calendario mostrará:

- Planificaciones futuras.
- Eventos realizados.
- Registros relevantes.

La planificación es la fuente de actividades futuras.

---

# Automatización futura

Una planificación puede generar:

- Recordatorios.
- Notificaciones.
- Eventos automáticos.
- Solicitudes de registro.

---

# Estadísticas

La planificación permite analizar:

- Cumplimiento de actividades.
- Retrasos.
- Diferencias entre ciclos.
- Efectividad del manejo.

---

# Reglas de Negocio

- Una planificación pertenece a una Timeline.
- Una planificación no implica que la acción haya ocurrido.
- Un evento puede vincularse a una planificación.
- Una planificación debe conservar su estado histórico.
- La modificación de una planificación no debe alterar eventos existentes.
- Una planificación puede repetirse durante una fase.

---

# Estado

🟡 En revisión (Versión 1)