# Registro

## Definición

Un Registro representa un dato medido, observado o capturado durante el desarrollo de un Cultivo.

Los registros forman parte de la Timeline y permiten almacenar información cuantificable del estado del cultivo.

---

# Objetivo

Registrar información objetiva del cultivo para:

- Seguimiento diario.
- Comparaciones históricas.
- Análisis estadístico.
- Evaluación de resultados.

---

# Responsabilidades

Un Registro debe:

- Pertenecer a una Timeline.
- Asociarse a una fase del cultivo.
- Registrar fecha y responsable.
- Almacenar valores medidos.
- Mantener historial.

---

# Relación

Un Registro pertenece a:

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
Timeline

 |
 |
Fase

 |
 |
Registro
```

---

# Tipos de Registro

Versión inicial:

## Ambiental

Ejemplos:

- Temperatura.
- Humedad relativa.
- VPD.
- CO₂.
- PPFD.

---

## Nutricional

Ejemplos:

- PH.
- EC.
- Runoff.
- Cantidad aplicada.

---

## Desarrollo

Ejemplos:

- Altura.
- Número de plantas.
- Estado general.
- Observaciones visuales.

---

## Productivo

Ejemplos:

- Peso cosecha.
- Calidad.
- Rendimiento.

---

# Información General

| Atributo | Descripción |
|-|-|
| Timeline | Línea temporal asociada |
| Fase | Etapa del cultivo |
| Tipo | Tipo de registro |
| Fecha | Momento de medición |
| Usuario | Responsable |
| Valor | Dato registrado |
| Unidad | Unidad del valor |
| Observación | Comentarios |

---

# Ejemplo

```
Registro Ambiental

Fecha:
20/03/2026

Temperatura:
26 °C

Humedad:
60 %

VPD:
1.15

Usuario:
Juan
```

---

# Registros históricos

Los registros no se reemplazan.

Cada medición genera un nuevo registro.

Ejemplo:

```
10/03
VPD 1.1

11/03
VPD 1.2

12/03
VPD 1.3
```

Esto permite analizar evolución.

---

# Automatización futura

Los registros pueden provenir de:

- Carga manual.
- Sensores.
- Sistemas externos.

---

# Estadísticas

Los registros permitirán generar:

- Promedios ambientales.
- Evolución por fase.
- Comparación entre salas.
- Comparación entre variedades.
- Relación ambiente-producción.

---

# Reglas de Negocio

- Un registro pertenece a una Timeline.
- Un registro debe conservar fecha.
- Los valores históricos no deben modificarse eliminando información.
- Los registros deben mantener unidad de medida.
- Un registro puede asociarse a una fase específica.

---

# Estado

🟡 En revisión (Versión 1)