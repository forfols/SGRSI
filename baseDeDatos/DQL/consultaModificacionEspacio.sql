SELECT
    m.id,
    m.ciAdministrador,
    u.nombre AS nombreAdministrador,
    m.tipo,
    m.numero,
    m.accion,
    m.cantidadAnterior,
    m.cantidadNueva,
    m.fecha
FROM MODIFICACIONESPACIO AS m
    INNER JOIN USUARIO AS u ON m.ciAdministrador = u.ci
ORDER BY m.fecha DESC;