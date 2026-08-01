# Variedad

## Definición

Una Variedad representa una genética participante dentro de un Cultivo.

Un mismo Cultivo puede contener una o varias variedades.

Una misma genética puede participar en múltiples Cultivos distintos.

---

## Responsabilidades

- Identificar la genética.
- Registrar la cantidad de plantas.
- Mantener información propia dentro del Cultivo.

---

## Información

| Atributo | Descripción |
|----------|-------------|
| Genética | Referencia al catálogo de variedades. |
| Cantidad Plantas | Número de plantas. |
| Fecha Ingreso | Inicio dentro del cultivo. |
| Estado | Activa, Cosechada, Perdida, Eliminada. |

---

## Relaciones

Pertenece a:

- Cultivo.

Referencia:

- Catálogo de Variedades.

---

## Reglas de Negocio

- Un Cultivo puede contener varias Variedades.
- Una Variedad puede recibir Eventos independientes.
- Una Variedad puede recibir Registros independientes.
- Un Evento puede afectar una o varias Variedades.

---

## Estado

🟢 Estable (Versión 1)