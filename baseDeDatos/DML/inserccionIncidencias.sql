INSERT INTO REGISTROESPACIO (idEspacio, nombreGrupo) VALUES (1, '1MA');
INSERT INTO REGISTROESPACIO (idEspacio, nombreGrupo) VALUES (7, '2MB');
INSERT INTO REGISTROESPACIO (idEspacio, nombreGrupo) VALUES (10, '3MC');
INSERT INTO REGISTROESPACIO (idEspacio, nombreGrupo) VALUES (2, '1MB');

INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES ('PC', 2, 'Juan Pérez', 'El monitor no enciende');
INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES ('Otros', NULL, NULL, 'El pizarrón está grafiteado');
INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES ('PC', 6, 'María Rodriguez', 'El teclado tiene varias teclas flojas y no responde el mouse');
INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES ('Otros', NULL, NULL, 'A la mesa del docente le faltan patas');
INSERT INTO REGISTROTIPOINCIDENCIA (tipo, idEquipo, alumno, descripcion) VALUES ('PC', 1, 'Salvador Medina', 'Inicia en pantalla azul de error al cargar el sistema operativo');


INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A');
INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('En proceso', 'Alta', 'La rayadura parece ser de cuchillo', 'N/A');
INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Terminado', 'Media', 'El teclado esta dañado y el mouse está desenchufado', 'Se cambió el teclado y se enchufó el mouse. Ahora funcionan');
INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('Sin asignar', 'Sin asignar', 'N/A', 'N/A');
INSERT INTO ESTADO (tipo, prioridad, diagnostico, soluciones) VALUES ('En proceso', 'Baja', 'Disco HDD desconfigurado', 'N/A');

INSERT INTO REGISTROINCIDENCIA (ciSolicitante, ciTecnico, idRegistroEspacio, idTipoIncidencia, idEstado, fecha) VALUES ('11111111', NULL, 1, 1, 1, NOW());
INSERT INTO REGISTROINCIDENCIA (ciSolicitante, ciTecnico, idRegistroEspacio, idTipoIncidencia, idEstado, fecha) VALUES ('11111111', '44444444', 3, 2, 2, NOW());
INSERT INTO REGISTROINCIDENCIA (ciSolicitante, ciTecnico, idRegistroEspacio, idTipoIncidencia, idEstado, fecha) VALUES ('22222222', '19191919', 2, 3, 3, NOW());
INSERT INTO REGISTROINCIDENCIA (ciSolicitante, ciTecnico, idRegistroEspacio, idTipoIncidencia, idEstado, fecha) VALUES ('33333333', NULL, 3, 4, 4, NOW());
INSERT INTO REGISTROINCIDENCIA (ciSolicitante, ciTecnico, idRegistroEspacio, idTipoIncidencia, idEstado, fecha) VALUES ('99999999', '44444444', 4, 5, 5, NOW());