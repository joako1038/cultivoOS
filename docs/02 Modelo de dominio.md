# Principios del Dominio

## Evento

Un Evento representa una acción realizada.

Es la ejecución de una operación sobre el cultivo, una planta, una sala o cualquier otro elemento del sistema.

Puede modificar el estado de una o varias entidades.

Ejemplos:

- Cambio de fase.
- Trasplante.
- Poda.
- Preparación de solución nutritiva.
- Cambio de luminaria.
- Cosecha.

---

## Registro

Un Registro representa información observada.

No modifica el estado del sistema.

Su objetivo es documentar el estado de un cultivo en un momento determinado.

Puede ser generado una o varias veces por jornada según la configuración del cultivo.

Ejemplos:

- Temperatura.
- Humedad.
- VPD.
- EC.
- pH.
- PPFD.
- Observaciones.

---

## Planificación

Una Planificación representa una acción futura.

No implica que la acción haya ocurrido.

Su ciclo de vida finaliza cuando un Evento asociado confirma su ejecución.

Ejemplos:

- Programar un trasplante.
- Programar un cambio de fase.
- Programar una poda.
- Programar una cosecha.