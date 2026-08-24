<<<<<<< HEAD
/*
    Espacio donde se deberán colocar todas las sentencias utilizadas para crear las tablas.
*/

CREATE TABLE USUARIO (
    ci VARCHAR(20) NOT NULL,
    contra VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT pk_usuario
        PRIMARY KEY (ci)
);

CREATE TABLE SOLICITANTE (
    ci VARCHAR(20) NOT NULL,

    CONSTRAINT pk_solicitante
        PRIMARY KEY (ci)
);

CREATE TABLE TECNICO (
    ci VARCHAR(20) NOT NULL,

    CONSTRAINT pk_tecnico
        PRIMARY KEY (ci)
);

CREATE TABLE ADMINISTRADOR (
    ci VARCHAR(20) NOT NULL,

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
=======
CREATE TABLE usuario (
    cedula CHAR(8) NOT NULL,
    contra VARCHAR(255) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,
    rol VARCHAR(20) NOT NULL,

    CONSTRAINT pk_usuario
        PRIMARY KEY (cedula)
);

CREATE TABLE administrador (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_administrador
        PRIMARY KEY (cedula)
);

CREATE TABLE solicitante (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_solicitante
        PRIMARY KEY (cedula)
);

CREATE TABLE tecnico (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_tecnico
        PRIMARY KEY (cedula)
);

ALTER TABLE administrador
    ADD CONSTRAINT fk_administrador_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);

ALTER TABLE solicitante
    ADD CONSTRAINT fk_solicitante_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);

ALTER TABLE tecnico
    ADD CONSTRAINT fk_tecnico_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);
>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
