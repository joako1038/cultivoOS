# ConfiguraciónCultivo

## Definición

ConfiguraciónCultivo representa los parámetros particulares que definen cómo será gestionado y monitoreado un Cultivo.

Permite adaptar el sistema a las características reales del cultivo, evitando imponer una estructura fija para todos los casos.

Cada Cultivo posee su propia configuración.

---

# Objetivo

Definir:

- Características productivas del cultivo.
- Sistema utilizado.
- Método de manejo.
- Variables de seguimiento necesarias.
- Frecuencia de registros.
- Particularidades del ciclo.

---

# Relación

ConfiguraciónCultivo pertenece a:

- Un Cultivo.

Define el comportamiento de:

- Timeline.
- Registros.
- Seguimiento.
- Planificación futura.

---

# Modelo conceptual

```
Cultivo

 |
 |
 └── ConfiguraciónCultivo

          |
          |
          ├── Parámetros productivos
          |
          ├── Sistema de cultivo
          |
          ├── Variables de seguimiento
          |
          └── Frecuencia de registros
```

---

# Información General

| Atributo | Descripción |
|-|-|
| Cultivo | Cultivo asociado |
| Tipo de cultivo | Característica general del ciclo |
| Sistema productivo | Método utilizado |
| Estado | Activo / Finalizado |
| Observaciones | Información adicional |

---

# Tipo de Cultivo

Define la finalidad o etapa productiva principal.

Ejemplos:

- Vegetativo.
- Floración.
- Madres.
- Esquejes.
- Producción.

---

# Sistema Productivo

Define la metodología utilizada.

Ejemplos:

## Medio de cultivo

- Tierra.
- Coco.
- Hidroponía.
- Aeroponía.
- Otro.

---

## Sistema de riego

Ejemplos:

- Manual.
- Automático.
- Goteo.
- Inundación.
- Otro.

---

## Manejo nutricional

Ejemplos:

- Fertilización manual.
- Dosificación automática.
- Solución recirculante.
- Otro.

---

# Variables de seguimiento

La configuración define qué información necesita registrar el cultivo.

No todos los cultivos requieren las mismas variables.

---

## Variables base

Variables comunes:

- Fecha.
- Usuario responsable.
- Estado del cultivo.
- Observaciones generales.

---

## Variables ambientales

Ejemplos:

- Temperatura.
- Humedad relativa.
- VPD.
- CO₂.
- PPFD.

---

## Variables nutricionales

Ejemplos:

- PH.
- EC.
- Temperatura de solución.
- Runoff.
- Concentraciones.

---

## Variables hidráulicas

Ejemplos:

- Litros aplicados.
- Litros consumidos.
- Nivel de depósito.
- Tiempo de riego.

---

## Variables productivas

Ejemplos:

- Altura.
- Desarrollo.
- Peso.
- Producción final.

---

# Frecuencia de seguimiento

La configuración define cuántos registros espera el cultivo.

Ejemplos:

## Seguimiento básico

```
1 registro diario

08:00
```

---

## Seguimiento intensivo

```
3 registros diarios

08:00
14:00
20:00
```

---

# Relación con Registros

La configuración determina qué registros pueden generarse.

Ejemplo:

Configuración:

```
Sistema:
Hidroponía automática

Variables:

PH
EC
Temperatura solución
Litros consumidos
```

Registros generados:

```
Registro 08:00

PH:
6.1

EC:
1.8

Temperatura:
21°C

Litros:
5L
```

---

# Relación con Eventos

La configuración también puede modificar qué eventos tienen sentido para el cultivo.

Ejemplo:

Cultivo con riego automático:

No genera:

```
Evento:
Riego manual
```

Puede generar:

```
Evento:
Preparación de solución nutritiva
```

Mientras que un cultivo manual puede tener:

```
Evento:
Aplicación de riego
```

---

# Personalización

Cada cultivo puede tener configuraciones particulares.

Ejemplo:

Cultivo A:

```
Indoor
Hidroponía
Riego automático

Registros:

PH
EC
VPD
Litros consumidos
```

---

Cultivo B:

```
Outdoor
Sustrato

Registros:

Humedad suelo
Temperatura
Riego aplicado
```

---

# Relación con Timeline

La configuración se utiliza al crear y operar la Timeline.

Flujo:

```
Crear Cultivo

      ↓

Definir ConfiguraciónCultivo

      ↓

Crear Timeline

      ↓

Generar seguimiento

      ↓

Registrar eventos y valores
```

---

# Reglas de Negocio

- Todo Cultivo debe tener una ConfiguraciónCultivo.
- La configuración pertenece exclusivamente a ese cultivo.
- Cambiar la configuración no debe eliminar registros históricos.
- Los registros deben respetar la configuración activa.
- Un cultivo puede evolucionar y modificar su configuración.
- Los cambios de configuración deben quedar registrados.

---

# Estado

🟡 En revisión (Versión 1)