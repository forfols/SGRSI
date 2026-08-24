SELECT
<<<<<<< HEAD
    u.ci,
    u.contra,
    u.nombre,
    u.activo,

    CASE
        WHEN s.ci IS NOT NULL THEN 1
        ELSE 0
    END AS solicitante,

    CASE
        WHEN t.ci IS NOT NULL THEN 1
        ELSE 0
    END AS tecnico,

    CASE
        WHEN a.ci IS NOT NULL THEN 1
        ELSE 0
    END AS administrador

FROM USUARIO AS u

    LEFT JOIN SOLICITANTE AS s
        ON s.ci = u.ci

    LEFT JOIN TECNICO AS t
        ON t.ci = u.ci

    LEFT JOIN ADMINISTRADOR AS a
        ON a.ci = u.ci

WHERE u.ci = :ci;
=======
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
>>>>>>> 9384d8451fc88a4e58eea6409ea3d7dae60e0d87
