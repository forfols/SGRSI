CREATE TABLE MODIFICACIONESPACIO (
    id INT(20) AUTO_INCREMENT,
    ciAdministrador VARCHAR(20) NOT NULL,
    tipo VARCHAR(255) NOT NULL,
    numero INT(10) NOT NULL,
    accion VARCHAR(50) NOT NULL,
    cantidadAnterior INT(10) NULL,
    cantidadNueva INT(10) NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_modificacionEspacio
        PRIMARY KEY (id),

    CONSTRAINT fk_modificacion_administrador
        FOREIGN KEY (ciAdministrador)
        REFERENCES ADMINISTRADOR(ci)
);