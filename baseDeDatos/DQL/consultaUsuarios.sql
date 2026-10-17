SELECT
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