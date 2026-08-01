# Evento

## Definición

Un Evento representa una acción, cambio o acontecimiento ocurrido durante el desarrollo de un Cultivo.

Los eventos forman parte de la Timeline y permiten registrar la historia operativa del cultivo.

Un evento representa algo que ocurrió, no algo planificado.

---

# Objetivo

Registrar las acciones realizadas durante un ciclo productivo.

Permitir conocer:

- Qué ocurrió.
- Cuándo ocurrió.
- Quién lo realizó.
- Sobre qué parte del cultivo actuó.
- Qué información quedó asociada.

---

# Responsabilidades

Un Evento debe:

- Pertenecer a una Timeline.
- Asociarse a una Fase del Cultivo.
- Registrar responsable.
- Mantener fecha de ejecución.
- Conservar información histórica.
- Permitir análisis posteriores.

---

# Relación

Un Evento pertenece a:

- Una Timeline.
- Una FaseCultivo.

Puede afectar:

- Cultivo completo.
- CultivoVariedad.
- Futuramente planta individual.

---

# Modelo conceptual

```
Cultivo

 |
 |
 └── Timeline

        |
        |
        └── Fase

              |
              |
              └── Evento
```

---

# Tipos de Evento

Versión inicial:

| Tipo | Ejemplos |
|-|-|
| Riego | Aplicación de agua o solución nutritiva |
| Fertilización | Aplicación de nutrientes |
| Poda | Poda apical, limpieza, entrenamiento |
| Trasplante | Cambio de contenedor o sistema |
| Cambio ambiental | Luz, temperatura, humedad |
| Aplicación | Preventivos o tratamientos |
| Inspección | Revisión visual |
| Cosecha | Inicio o finalización |
| Otro | Evento personalizado |

---

# Información General

| Atributo | Descripción |
|-|-|
| Timeline | Línea temporal asociada |
| Fase | Etapa donde ocurre |
| Tipo | Clasificación del evento |
| Fecha | Momento de ejecución |
| Usuario | Persona responsable |
| Descripción | Detalle del evento |
| Estado | Realizado, Cancelado |

---

# Información adicional

Un evento puede requerir información específica según su tipo.

Ejemplo:

Evento:
Fertilización

Datos:

- Producto utilizado.
- Cantidad.
- Método de aplicación.
- Observaciones.

Evento:
Poda

Datos:

- Tipo de poda.
- Cantidad intervenida.
- Observaciones.

---

# Eventos y Variedades

Un evento puede aplicarse:

A todo el cultivo:

Ejemplo:

Cambio de fotoperiodo.

O a una variedad específica:

Ejemplo:

Tratamiento únicamente sobre Tropicana WFC.

---

# Usuario responsable

Todo evento debe conservar quién realizó la acción.

Ejemplo:

```
Evento:
Riego

Fecha:
15/02/2026

Usuario:
Juan Pérez
```

---

# Relación con Planificación

Un evento puede originarse desde una planificación.

Ejemplo:

Planificado:

"Realizar poda día 25"

Resultado:

Evento:

"Poda realizada día 27"

La planificación y el evento son conceptos diferentes.

---

# Estadísticas

Los eventos permitirán analizar:

- Frecuencia de actividades.
- Tiempo dedicado.
- Manejos aplicados.
- Relación entre acciones y resultados.
- Diferencias entre cultivos.

---

# Reglas de Negocio

- Un evento pertenece a una Timeline.
- Un evento debe tener fecha y responsable.
- Un evento realizado no debe perderse del historial.
- Un evento puede tener información específica según su tipo.
- Un evento puede afectar diferentes niveles del cultivo.

---

# Estado

🟡 En revisión (Versión 1)