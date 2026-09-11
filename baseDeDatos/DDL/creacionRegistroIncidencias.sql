CREATE TABLE ESPACIO (
    id INT(20) AUTO_INCREMENT,
    tipo VARCHAR(255) NOT NULL,
    numero INT(10) NOT NULL,

    CONSTRAINT pk_Espacio
        PRIMARY KEY (id)
);

CREATE TABLE GRUPO (
    nombre VARCHAR(255) NOT NULL,

    CONSTRAINT pk_Grupo
        PRIMARY KEY (nombre)
);

CREATE TABLE REGISTROESPACIO (
    id INT(20) AUTO_INCREMENT,
    idEspacio INT(20) NOT NULL,
    nombreGrupo VARCHAR(255) NOT NULL,

    CONSTRAINT pk_registroEspacio
        PRIMARY KEY (id),

    CONSTRAINT fk_Espacio
        FOREIGN KEY (idEspacio)
        REFERENCES ESPACIO(id),

    CONSTRAINT fk_Grupo
        FOREIGN KEY (nombreGrupo)
        REFERENCES GRUPO(nombre)
);

CREATE TABLE EQUIPO (
    id INT(20) AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,

    CONSTRAINT pk_Equipo
        PRIMARY KEY (id)
);

CREATE TABLE REGISTROTIPOINCIDENCIA (
    id INT(20) AUTO_INCREMENT,
    tipo VARCHAR(255) NOT NULL,
    idEquipo INT(20) NULL,
    alumno VARCHAR(255) NULL,
    descripcion VARCHAR(255) NOT NULL,

    CONSTRAINT pk_registroTipoIncidencia
        PRIMARY KEY (id),
    
    CONSTRAINT fk_idEquipo
        FOREIGN KEY (idEquipo)
        REFERENCES EQUIPO(id)
);

CREATE TABLE ESTADO (
    id INT(20) AUTO_INCREMENT,
    tipo VARCHAR(50) DEFAULT 'Sin asignar',
    prioridad VARCHAR(50) DEFAULT 'Sin asignar',
    diagnostico VARCHAR(255) DEFAULT 'N/A',
    soluciones VARCHAR(255) DEFAULT 'N/A',

    CONSTRAINT pk_estado
        PRIMARY KEY (id)
);

CREATE TABLE REGISTROINCIDENCIA (
    id INT(20) AUTO_INCREMENT,

    ciSolicitante VARCHAR(20) NOT NULL,
    ciTecnico VARCHAR(20),

    idRegistroEspacio INT(20) NOT NULL,
    idTipoIncidencia INT(20) NOT NULL,

    idEstado INT(20) NOT NULL,

    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,


    CONSTRAINT pk_registroIncidencia
        PRIMARY KEY (id),

    CONSTRAINT fk_ciSolicitante
        FOREIGN KEY (ciSolicitante)
        REFERENCES USUARIO(ci),

    CONSTRAINT fk_ciTecnico
        FOREIGN KEY (ciTecnico)
        REFERENCES USUARIO(ci),

    CONSTRAINT fk_registroEspacio
        FOREIGN KEY (idRegistroEspacio)
        REFERENCES REGISTROESPACIO(id),

    CONSTRAINT fk_tipoIncidencia
        FOREIGN KEY (idTipoIncidencia)
        REFERENCES REGISTROTIPOINCIDENCIA(id),

    CONSTRAINT fk_estado
        FOREIGN KEY (idEstado)
        REFERENCES ESTADO(id)
); 