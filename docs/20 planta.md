# Planta

## Definición

La entidad Planta representa un individuo biológico dentro del sistema productivo.

Permite realizar trazabilidad individual, independientemente del Cultivo donde participe.

Una Planta posee una historia propia formada por eventos y participaciones dentro de cultivos.

---

# Objetivo

Permitir:

- Identificación individual.
- Seguimiento histórico.
- Asociación con variedad genética.
- Registro de eventos particulares.
- Generación de estadísticas individuales.

---

# Relación conceptual

```
VARIEDAD

    |
    |

 PLANTA

    |
    |

EVENTOS

    |
    |

CULTIVOS
```

---

# Datos principales

| Campo | Descripción |
|-|-|
| Identificador | Código único de planta |
| Variedad | Genética asociada |
| Fecha origen | Inicio de vida registrada |
| Estado actual | Situación actual |
| Observaciones | Información adicional |

---

# Identificación

Cada planta debe poder ser identificada individualmente.

Ejemplo:

```
P001
P002
P003
```

El identificador debe permanecer durante toda la vida de la planta.

---

# Estados posibles

Ejemplos:

- Esqueje.
- Vegetativo.
- Floración.
- Cosechada.
- Eliminada.
- Perdida.
- Madre.

---

# Relación con Cultivos

Una Planta puede participar en uno o varios Cultivos durante su ciclo de vida.

Ejemplo:

```
Planta P001

Cultivo:
Esquejes Julio

↓

Cultivo:
Producción Agosto
```

---

# Relación con Eventos

Los eventos permiten registrar cambios particulares de la planta.

Ejemplos:

- Ingreso a cultivo.
- Cambio de fase.
- Trasplante.
- Poda.
- Problema sanitario.
- Cosecha.

---

# Eventos de planta

Ejemplo:

```
Evento:

Tipo:
Cambio de fase

Planta:
P001

Anterior:
Vegetativo

Nuevo:
Floración

Fecha:
20/08/2026
```

---

# Estadísticas futuras

La trazabilidad individual permitirá analizar:

- Rendimiento por planta.
- Rendimiento por variedad.
- Historial completo.
- Tiempo en cada etapa.
- Diferencias entre individuos.

---

# Reglas de negocio

- Toda planta debe pertenecer a una variedad.
- Una planta posee un identificador único.
- Los cambios importantes deben registrarse mediante eventos.
- Una planta puede participar en múltiples cultivos.
- El historial de una planta no debe perderse.

---

# Estado

🟡 En revisión (Versión 1)