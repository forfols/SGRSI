/*
    Espacio donde se deberán aclarar y definir todas las consultas utilizadas dentro del sistema

    Ya que los select tendrán valores que dependerán de lo que ingrese el usuario, indicar siempre cuál es el valor
    que variará.
*/

/*Selecciona un usuario en base a su cédula, en PHP, donde aparece '00000000' debe ser remplazado por :cedula*/
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

FROM USUARIO AS u

    LEFT JOIN ADMINISTRADOR AS a
    ON a.cedula = u.cedula

    LEFT JOIN solicitante AS s
    ON s.cedula = u.cedula

    LEFT JOIN tecnico AS t
    ON t.cedula = u.cedula

WHERE u.cedula = '00000000';