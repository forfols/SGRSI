SELECT
    p.id,
    p.alumno,
    p.ciAlumno,
    l.id AS idLaptop,
    l.modelo,
    p.ciTecnico,
    u.nombre AS nombreTecnico,
    p.fechaInicio,
    p.fechaFin
FROM PRESTAMO AS p
    INNER JOIN LAPTOP AS l ON p.idLaptop = l.id
    INNER JOIN USUARIO AS u ON p.ciTecnico = u.ci
ORDER BY p.fechaInicio DESC;