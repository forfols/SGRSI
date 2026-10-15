SELECT
    l.id,
    l.modelo,
    CASE
        WHEN p.id IS NULL THEN 'Disponible'
        ELSE 'Prestada'
    END AS estado
FROM LAPTOP AS l
    LEFT JOIN PRESTAMO AS p
        ON p.idLaptop = l.id AND p.fechaFin IS NULL
WHERE l.activa = 1;