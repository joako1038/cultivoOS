# Sala

## Definición

Una Sala representa un espacio físico donde se desarrollan uno o varios cultivos a lo largo del tiempo.

La Sala describe la infraestructura permanente y el equipamiento disponible, independientemente de los cultivos que aloje.

---

## Responsabilidades

- Contener Cultivos.
- Definir las características físicas del ambiente.
- Mantener información sobre infraestructura.
- Mantener información sobre equipamiento.
- Conservar el historial de cultivos realizados.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre identificatorio de la sala. |
| Código | Identificador interno único. |
| Tipo | Vegetativa, Floración, Madres, Esquejes u otros. |
| Área | Superficie disponible en m². |
| Altura | Altura útil del espacio. |
| Volumen | Volumen disponible en m³. |
| Estado | Activa, Inactiva, Mantenimiento. |

---

## Infraestructura

Representa las características permanentes de la sala.

Ejemplos:

- Sistema de extracción.
- Sistema de intracción.
- Disponibilidad de CO₂.
- Instalación eléctrica.
- Sistema de agua.
- Drenajes.

---

## Equipamiento

La Sala puede tener equipamiento asociado.

Ejemplos:

- Luminarias.
- Extractores.
- Intractores.
- Aire acondicionado.
- Humidificadores.
- Deshumidificadores.
- Sensores.
- Bombas.

---

## Relaciones

Una Sala:

- Pertenece a una Empresa.
- Puede contener múltiples Cultivos.
- Puede tener múltiples Equipamientos.

---

## Relación con Cultivos

Una Sala no conoce las variedades ni la configuración productiva.

Su responsabilidad es proporcionar el ambiente físico donde se desarrolla un Cultivo.

Ejemplo:

Sala Flora 1:

- Cultivo Enero 2026.
- Cultivo Mayo 2026.
- Cultivo Septiembre 2026.

---

## Reglas de Negocio

- Una Sala puede existir sin Cultivos.
- Un Cultivo pertenece únicamente a una Sala.
- La configuración de cultivo no pertenece a la Sala.
- La infraestructura puede cambiar con el tiempo.

---

## Estado

🟢 Estable (Versión 1)