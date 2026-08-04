
SELECT
    ci,
    contra,
    nombre,
    rol,
    activo
FROM USUARIO
WHERE ci = :ci;