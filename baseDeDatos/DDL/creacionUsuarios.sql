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
