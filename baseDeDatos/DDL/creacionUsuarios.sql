/*
    Espacio donde se deberán colocar todas las sentencias utilizadas para crear las tablas.
*/

CREATE TABLE USUARIO (
    ci CHAR(8) NOT NULL,
    contra VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT pk_usuario
        PRIMARY KEY (ci)
);

CREATE TABLE SOLICITANTE (
    ci CHAR(8) NOT NULL,

    CONSTRAINT pk_solicitante
        PRIMARY KEY (ci)
);

CREATE TABLE TECNICO (
    ci CHAR(8) NOT NULL,

    CONSTRAINT pk_tecnico
        PRIMARY KEY (ci)
);

CREATE TABLE ADMINISTRADOR (
    ci CHAR(8) NOT NULL,

    CONSTRAINT pk_administrador
        PRIMARY KEY (ci)
);

ALTER TABLE SOLICITANTE
    ADD CONSTRAINT fk_solicitante_usuario
    FOREIGN KEY (ci)
    REFERENCES USUARIO (ci);

ALTER TABLE TECNICO
    ADD CONSTRAINT fk_tecnico_usuario
    FOREIGN KEY (ci)
    REFERENCES USUARIO (ci);

ALTER TABLE ADMINISTRADOR
    ADD CONSTRAINT fk_administrador_usuario
    FOREIGN KEY (ci)
    REFERENCES USUARIO (ci);