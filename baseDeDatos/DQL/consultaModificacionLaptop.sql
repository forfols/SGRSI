SELECT
    m.id,
    m.ciTecnico,
    u.nombre AS nombreTecnico,
    l.id AS idLaptop,
    l.modelo,
    m.accion,
    m.detalle,
    m.fecha
FROM MODIFICACIONLAPTOP AS m
    INNER JOIN USUARIO AS u ON m.ciTecnico = u.ci
    INNER JOIN LAPTOP AS l ON m.idLaptop = l.id
ORDER BY m.fecha DESC;