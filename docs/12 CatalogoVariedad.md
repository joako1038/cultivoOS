# Catálogo de Variedades

## Definición

El Catálogo de Variedades representa el registro general de las variedades genéticas disponibles para ser utilizadas en los cultivos.

Contiene información propia de la genética que no depende de una sala ni de un cultivo específico.

---

## Responsabilidades

- Identificar las variedades disponibles.
- Mantener información general de cada genética.
- Permitir reutilizar una misma variedad en múltiples cultivos.
- Servir como base histórica para futuros análisis.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre de la variedad. |
| Nombre alternativo | Otros nombres utilizados. |
| Banco / Origen | Procedencia de la genética. |
| Tipo | Índica, Sativa, Híbrida u otros. |
| Observaciones | Información adicional. |

---

## Características Técnicas

Información descriptiva de la genética.

Ejemplos:

- Tiempo estimado de floración.
- Características generales.
- Producción esperada.
- Perfil de crecimiento.
- Notas del cultivador.

---

## Relaciones

Un Catálogo de Variedad:

Puede estar asociado a:

- Muchos CultivoVariedad.

No pertenece a:

- Una Sala.
- Un Cultivo específico.

---

## Ejemplo

Catálogo:

Tropicana WFC

Información:

- Tipo: Híbrida.
- Floración estimada: 8-9 semanas.
- Observaciones: Producción alta.

Esta misma variedad puede participar en:

- Cultivo Enero 2026.
- Cultivo Junio 2026.
- Cultivo Marzo 2027.

---

## Reglas de Negocio

- Una variedad puede existir aunque nunca haya sido cultivada.
- Una variedad puede participar en múltiples cultivos.
- La información genética general no debe duplicarse por cultivo.

---

## Estado

🟡 En revisión (Versión 1)