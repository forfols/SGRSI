CREATE TABLE LAPTOP (
    id INT(20) AUTO_INCREMENT,
    modelo VARCHAR(255) NOT NULL,
    activa BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_laptop
        PRIMARY KEY (id)
);

CREATE TABLE PRESTAMO (
    id INT(20) AUTO_INCREMENT,
    alumno VARCHAR(255) NOT NULL,
    ciAlumno VARCHAR(20) NOT NULL,
    fechaInicio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fechaFin DATETIME NULL,
    idLaptop INT(20) NOT NULL,
    ciTecnico VARCHAR(20) NOT NULL,

    CONSTRAINT pk_prestamo
        PRIMARY KEY (id),

    CONSTRAINT fk_prestamo_laptop
        FOREIGN KEY (idLaptop)
        REFERENCES LAPTOP(id),

    CONSTRAINT fk_prestamo_tecnico
        FOREIGN KEY (ciTecnico)
        REFERENCES TECNICO(ci)
);
