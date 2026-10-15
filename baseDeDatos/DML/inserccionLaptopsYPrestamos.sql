INSERT INTO LAPTOP (modelo) VALUES
('ceibal1'),
('ceibal2'),
('ceibal3'),
('ceibal4'),
('ceibal5'),
('ceibal6'),
('ceibal7'),
('ceibal8'),
('ceibal9'),
('ceibal10');

INSERT INTO PRESTAMO (alumno, ciAlumno, fechaInicio, fechaFin, idLaptop, ciTecnico) VALUES
('Juan Pérez', '55555555', NOW(), NULL, 1, '44444444'),
('María Rodriguez', '56565656', NOW(), NULL, 2, '19191919');

INSERT INTO MODIFICACIONLAPTOP (ciTecnico, idLaptop, accion, detalle)
VALUES ('44444444', 3, 'Alta', 'Se agregó la laptop al catálogo');