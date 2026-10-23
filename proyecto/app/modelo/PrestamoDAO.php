<?php
class PrestamoDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarPrestamos(): array
    {
        $sql = "
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

            INNER JOIN LAPTOP AS l
                ON p.idLaptop = l.id

            INNER JOIN USUARIO AS u
                ON p.ciTecnico = u.ci

            ORDER BY p.fechaInicio DESC
        ";

        $consulta = $this->conexion->query($sql);

        $prestamos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $prestamos;
    }

    //Laptops activas que no tienen un préstamo abierto (fechaFin vacía)
    public function listarLaptopsDisponibles(): array
    {
        $sql = "
            SELECT l.id, l.modelo

            FROM LAPTOP AS l

            WHERE l.activa = 1
            AND NOT EXISTS (
                SELECT 1
                FROM PRESTAMO AS p
                WHERE p.idLaptop = l.id AND p.fechaFin IS NULL
            )

            ORDER BY l.id
        ";

        $consulta = $this->conexion->query($sql);

        $laptops = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $laptops;
    }

    public function buscarPrestamo(int $idPrestamo): ?array
    {
        $consulta = $this->conexion->prepare("SELECT id, fechaFin FROM PRESTAMO WHERE id = :id");
        $consulta->execute(["id" => $idPrestamo]);

        $prestamo = $consulta->fetch(PDO::FETCH_ASSOC);

        $consulta = null;

        if ($prestamo === false) {
            return null;
        }

        return $prestamo;
    }

    public function registrarPrestamo(string $alumno, string $ciAlumno, int $idLaptop, string $ciTecnico): bool
    {
        try {
            $this->conexion->beginTransaction();

            //FOR UPDATE bloquea la fila: si dos técnicos prestan la misma laptop a la vez, el segundo espera y la ve ocupada
            $sqlLaptop = "SELECT id FROM LAPTOP WHERE id = :idLaptop AND activa = 1 FOR UPDATE";
            $consultaLaptop = $this->conexion->prepare($sqlLaptop);
            $consultaLaptop->execute(["idLaptop" => $idLaptop]);
            $laptop = $consultaLaptop->fetch(PDO::FETCH_ASSOC);

            if ($laptop === false) {
                $this->conexion->rollBack();
                return false;
            }

            $sqlAbierto = "SELECT 1 FROM PRESTAMO WHERE idLaptop = :idLaptop AND fechaFin IS NULL";
            $consultaAbierto = $this->conexion->prepare($sqlAbierto);
            $consultaAbierto->execute(["idLaptop" => $idLaptop]);
            $abierto = $consultaAbierto->fetchColumn();

            if ($abierto !== false) {
                $this->conexion->rollBack();
                return false;
            }

            $sqlPrestamo = "INSERT INTO PRESTAMO (alumno, ciAlumno, idLaptop, ciTecnico) VALUES (:alumno, :ciAlumno, :idLaptop, :ciTecnico)";
            $consultaPrestamo = $this->conexion->prepare($sqlPrestamo);
            $consultaPrestamo->execute(["alumno" => $alumno, "ciAlumno" => $ciAlumno, "idLaptop" => $idLaptop, "ciTecnico" => $ciTecnico]);

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    public function devolverPrestamo(int $idPrestamo): bool
    {
        try {
            $consulta = $this->conexion->prepare("UPDATE PRESTAMO SET fechaFin = NOW() WHERE id = :id AND fechaFin IS NULL");
            $consulta->execute(["id" => $idPrestamo]);

            return $consulta->rowCount() > 0;

        } catch (PDOException $error) {
            return false;
        }
    }
}