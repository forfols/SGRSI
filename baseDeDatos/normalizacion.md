# Justificación de la Normalización a Tercera Forma Normal (3FN)

## Primera Forma Normal (1FN)

### ESPACIO

ESPACIO(
    id,
    tipo,
    numero
)

Cada atributo contiene un único valor:

id: identifica un espacio.
tipo: indica si es Laboratorio, Taller o Teórico.
numero: identifica el número del espacio.

No existen atributos que almacenen múltiples valores dentro de una misma celda.

### GRUPO

GRUPO(
    nombre
)

Cada registro representa un único grupo y "nombre" funciona como pk.

### REGISTROESPACIO

REGISTROESPACIO(
    id,
    idEspacio,
    nombreGrupo
)

Esta relación permite representar la asociación entre un espacio y un grupo. Cada registro contiene un único espacio y un único grupo.

Esto es importante porque un grupo puede utilizar diferentes espacios y un espacio puede ser utilizado por diferentes grupos en distintos registros. Por lo tanto, no se almacenan varios grupos dentro de una misma fila de ESPACIO.

### REGISTROTIPOINCIDENCIA

REGISTROTIPOINCIDENCIA(
    id,
    tipo,
    nroPc,
    alumno,
    descripcion
)

Los atributos contienen valores individuales. nroPc y alumno pueden ser null o tener un único valor dependiendo del tipo de incidencia.

### ESTADO

ESTADO(
    id,
    tipo,
    prioridad,
    diagnostico,
    soluciones
)

Cada atributo contiene un único valor correspondiente al estado de una incidencia.

### REGISTROINCIDENCIA

REGISTROINCIDENCIA(
    id,
    ciSolicitante,
    ciTecnico,
    idRegistroEspacio,
    idTipoIncidencia,
    idEstado,
    fecha
)

La tabla almacena referencias individuales mediante claves foráneas. Por ejemplo, ciSolicitante contiene una única cédula y idEstado un único identificador de estado.

### USUARIO

USUARIO(
    ci,
    contra,
    nombre,
    activo
)

Cada atributo contiene un único valor correspondiente al usuario.

Por lo tanto, todas las relaciones cumplen 1FN.



## Segunda Forma Normal (2FN)

Para alcanzar la Segunda Forma Normal, además de cumplir 1FN, todos los atributos que no sean pk deben depender completamente de la clave primaria.

Las tablas utilizan claves primarias simples, por ejemplo:

ESPACIO -> id
GRUPO -> nombre
REGISTROESPACIO -> id
REGISTROINCIDENCIA -> id
USUARIO -> ci

Al existir una única columna como clave primaria en cada relación, no pueden existir dependencias parciales respecto de una parte de una clave compuesta.

Por ejemplo, en ESPACIO:
El tipo y el numero dependen completamente de id.

En USUARIO:
Los datos del usuario dependen completamente de su ci.

En REGISTROINCIDENCIA:
Todos estos atributos describen a la incidencia identificada por id.

Por lo tanto, el modelo cumple la 2FN.

## Tercera Forma Normal (3FN)

Para cumplir la Tercera Forma Normal, además de cumplir 1FN y 2FN, ningún atributo no clave debe depender de otro atributo no clave.
Esto se logra separando las entidades y relacionándolas mediante claves foráneas.

ESPACIO(
    id PK,
    tipo,
    numero
)

tipo y numero dependen de id y no dependen entre sí.

Por ejemplo, no se almacena información del grupo dentro de ESPACIO.

GRUPO(
    nombre PK
)

El grupo tiene únicamente su identificador.
Se mantiene como una tabla independiente porque los grupos son una entidad propia del sistema y pueden relacionarse con los espacios.

Relación entre ESPACIO, GRUPO y REGISTROESPACIO:

REGISTROESPACIO(
    id PK,
    idEspacio FK,
    nombreGrupo FK
)

Existe esta tabla porque la relación entre ESPACIO y GRUPO no debe almacenarse directamente dentro de ninguna de las dos tablas. Además, esto permite que:

Un espacio pueda aparecer en varios registros.
Un grupo pueda aparecer en varios registros.
Que cada registro represente una utilización/asignación concreta de un grupo a un espacio.

Por lo tanto, REGISTROESPACIO funciona como relación entre ambas entidades.

REGISTROTIPOINCIDENCIA(
    id PK,
    tipo,
    nroPc,
    alumno,
    descripcion
)

Los atributos dependen de id.
La información específica de una incidencia se mantiene separada de REGISTROINCIDENCIA.
Esto evita almacenar directamente todos esos datos dentro de REGISTROINCIDENCIA.


ESTADO(
    id PK,
    tipo,
    prioridad,
    diagnostico,
    soluciones
)

Los atributos dependen de id.
Se utiliza una tabla independiente porque el estado de una incidencia puede modificarse posteriormente sin modificar la información principal de la incidencia.
Además, cada incidencia posee su propio registro en ESTADO, permitiendo que una incidencia tenga inicialmente:

tipo = Sin asignar
prioridad = Sin asignar
diagnostico = N/A
soluciones = N/A

y posteriormente pueda ser actualizado por un tecnico o administrador.


REGISTROINCIDENCIA(
    id PK,
    ciSolicitante FK,
    ciTecnico FK,
    idRegistroEspacio FK,
    idTipoIncidencia FK,
    idEstado FK,
    fecha
)
Actúa como la entidad central del sistema.
En lugar de repetir información de otras entidades, utiliza claves foráneas.
Esto evita almacenar datos como:

nombre del solicitante, nombre del técnico, tipo de espacio, número del espacio, grupo, estado y prioridad directamente en REGISTROINCIDENCIA.
Lo reduce considerablemente la redundancia.

Relación con USUARIO
REGISTROINCIDENCIA tiene dos relaciones con USUARIO:

ciSolicitante -> USUARIO(ci)
ciTecnico -> USUARIO(ci)

Esto se debe a que una incidencia involucra dos roles diferentes de un usuario:
un usuario que registra la incidencia;
un usuario que actúa como técnico.

Se utilizan dos claves foráneas hacia la misma tabla porque ambos representan usuarios, pero cumplen funciones diferentes dentro de la incidencia.

ciTecnico puede ser NULL porque una incidencia puede ser creada antes de que un técnico sea asignado.




Relación USUARIO con los roles

Las tablas:

SOLICITANTE(ci)
TECNICO(ci)
ADMINISTRADOR(ci)

utilizan ci como clave primaria y al mismo tiempo como clave foránea hacia USUARIO.

Por ejemplo:

SOLICITANTE(
    ci PK, FK -> USUARIO(ci)
)

Esto permite almacenar en USUARIO los datos generales: ci, contra, nombre y activo, manteniendo los roles separados.
De esta forma, si un usuario posee varios roles, puede existir en varias de estas tablas sin duplicar su información personal.
