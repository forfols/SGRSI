SELECT
    u.cedula,
    u.contra,
    u.activo,
    u.rol,

    CASE
        WHEN a.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS administrador,

    CASE
        WHEN s.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS solicitante

    CASE
        WHEN t.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS tecnico

FROM usuario AS u

    LEFT JOIN administrador AS a
    ON a.cedula = u.cedula

    LEFT JOIN solicitante AS s
    ON s.cedula = u.cedula

    LEFT JOIN tecnico AS t
    ON t.cedula = u.cedula

WHERE u.cedula = '00000000';
