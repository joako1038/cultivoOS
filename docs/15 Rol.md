# Rol

## Definición

Un Rol representa un conjunto de capacidades asignadas a un Usuario dentro de una Organización.

Permite definir qué acciones puede realizar cada participante.

---

## Roles iniciales

Versión inicial:

### Administrador

Responsable general de la organización.

Puede:

- Gestionar usuarios.
- Gestionar roles.
- Gestionar salas.
- Gestionar cultivos.
- Acceder a toda la información.

---

### Responsable de Cultivo

Encargado de la gestión operativa de cultivos.

Puede:

- Crear y modificar información del cultivo.
- Gestionar eventos.
- Revisar registros.
- Consultar historial.

---

### Cultivador

Usuario operativo.

Puede:

- Registrar actividades.
- Completar eventos asignados.
- Cargar mediciones.
- Agregar observaciones.

---

### Visualizador

Usuario con acceso de consulta.

Puede:

- Ver información.
- Consultar historial.

No puede modificar datos.

---

## Información General

| Atributo | Descripción |
|----------|-------------|
| Nombre | Nombre del rol. |
| Descripción | Función dentro de la organización. |
| Estado | Activo, Inactivo. |

---

## Relaciones

Un Rol:

Puede pertenecer a:

- Muchos Usuarios.

Un Usuario:

Puede tener:

- Uno o varios Roles.

---

## Reglas de Negocio

- Los roles determinan capacidades dentro de la organización.
- Un usuario puede combinar roles.
- Los permisos específicos podrán evolucionar en futuras versiones.
- Las acciones importantes deben registrar el usuario responsable.

---

## Estado

🟡 En revisión (Versión 1)