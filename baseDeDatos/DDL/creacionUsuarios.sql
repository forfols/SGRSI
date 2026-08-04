/*
    Espacio donde se deberán colocar todas las sentencias utilizadas para crear las tablas.
*/

CREATE TABLE USUARIO (
    ci CHAR(8) NOT NULL,
    contra VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol VARCHAR(20) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT pk_usuario
        PRIMARY KEY (ci)
);