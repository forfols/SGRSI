CREATE TABLE REGISTROESPACIO (
    id INT(20) AUTO_INCREMENT,
    nombreEspacio VARCHAR(255) NOT NULL,
    nroEspacio INT(10) NOT NULL,
    grupo VARCHAR(255) NOT NULL,

    CONSTRAINT pk_registroEspacio
        PRIMARY KEY (id)
);

CREATE TABLE REGISTROTIPOINCIDENCIA (
    id INT(20) AUTO_INCREMENT,
    tipo VARCHAR(255) NOT NULL,
    nroPc VARCHAR(20),
    alumno VARCHAR(255) DEFAULT 'no aplica',
    descripcion VARCHAR(255) NOT NULL,

    CONSTRAINT pk_registroTipoIncidencia
        PRIMARY KEY (id)
);

CREATE TABLE REGISTROINCIDENCIA (
    id INT(20) AUTO_INCREMENT,

    nombreSolicitante VARCHAR(50) NOT NULL,
    ci CHAR(8) NOT NULL,
    idEspacio INT(20) NOT NULL,
    idTipoIncidencia INT(20) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50) DEFAULT 'Sin asignar',
    prioridad VARCHAR(50) DEFAULT 'Sin asignar',
    tecnicoAsignado VARCHAR(50) DEFAULT 'Sin asignar',
    diagnostico VARCHAR(255) DEFAULT 'N/A',
    soluciones VARCHAR(255) DEFAULT 'N/A',


    CONSTRAINT pk_registroIncidencia
        PRIMARY KEY (id),

    CONSTRAINT fk_incidenciaEspacio
        FOREIGN KEY (idEspacio)
        REFERENCES REGISTROESPACIO(id),

    CONSTRAINT fk_tipoIncidencia
        FOREIGN KEY (idTipoIncidencia)
        REFERENCES REGISTROTIPOINCIDENCIA(id)
); 