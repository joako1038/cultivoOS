# Usuario

## Definición

Un Usuario representa una persona que tiene acceso al sistema dentro de una Organización.

Los usuarios pueden participar en la gestión de salas, cultivos y registros según sus roles asignados.

---

## Responsabilidades

- Acceder al sistema.
- Ejecutar acciones permitidas.
- Registrar actividades.
- Participar en eventos del cultivo.
- Mantener trazabilidad de acciones realizadas.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre del usuario. |
| Email | Identificador de acceso. |
| Estado | Activo, Inactivo. |
| Organización | Organización a la que pertenece. |

---

## Relación con Roles

Un Usuario puede tener uno o varios Roles.

Ejemplo:

Usuario:

Juan Pérez

Roles:

- Administrador.
- Cultivador.

---

## Participación en Cultivos

Un Usuario puede intervenir en diferentes acciones.

Ejemplos:

- Crear un cultivo.
- Registrar un riego.
- Completar una tarea.
- Cargar una medición.
- Agregar observaciones.

La trazabilidad se registra mediante los eventos realizados.

---

## Reglas de Negocio

- Un Usuario pertenece a una Organización.
- Un Usuario puede tener múltiples Roles.
- Las acciones del Usuario deben quedar registradas.
- Un Usuario no obtiene acceso fuera de su Organización.

---

## Estado

🟡 En revisión (Versión 1)