/* crear usuario */
INSERT INTO usuario (cedula, contra, activo, rol) VALUES ('11111111', '123', true, 'administrador');

INSERT INTO usuario (cedula, contra, activo, rol) VALUES ('22222222', '456', true, 'solicitante');

INSERT INTO usuario (cedula, contra, activo, rol) VALUES ('33333333', '789', true, 'tecnico');

/* eliminar usuario */
DELETE FROM usuario WHERE cedula = '11111111';

DELETE FROM usuario WHERE cedula = '22222222';

DELETE FROM usuario WHERE cedula = '33333333';
