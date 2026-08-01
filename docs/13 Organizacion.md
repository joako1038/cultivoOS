# Organización

## Definición

Una Organización representa la entidad principal que utiliza el sistema.

Es el contenedor general donde se agrupan usuarios, salas y cultivos.

Una Organización puede representar diferentes formas de trabajo productivo.

---

## Tipos de Organización

Versión inicial:

- ONG
- Autocultivador

---

## Responsabilidades

- Gestionar usuarios participantes.
- Gestionar salas de cultivo.
- Gestionar cultivos.
- Definir reglas operativas según su tipo.
- Mantener historial de actividad.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre identificatorio de la organización. |
| Tipo | ONG, Autocultivador u otros futuros tipos. |
| Estado | Activa, Inactiva. |
| Fecha creación | Fecha de alta en el sistema. |
| Observaciones | Información adicional. |

---

## Relaciones

Una Organización:

Contiene:

- Usuarios.
- Roles.
- Salas.
- Cultivos.

---

## Reglas de Negocio

- Toda Sala pertenece a una Organización.
- Todo Cultivo pertenece a una Organización mediante su Sala.
- Todo Usuario pertenece a una Organización.
- Una Organización puede tener múltiples usuarios.
- Una Organización puede tener múltiples salas.

---

## Consideraciones futuras

La Organización podrá definir reglas operativas según su tipo.

Ejemplos:

- Límites productivos.
- Cantidad máxima de salas.
- Configuraciones permitidas.

Estas reglas no forman parte del núcleo del cultivo.

---

## Estado

🟡 En revisión (Versión 1)