<?php

/**
 * Clase que unifica todas las operaciones de acceso a datos
 * relacionadas con la entidad Laptop (listar, alta, baja).
 */
class LaptopDAO
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    //Laptops del catálogo (activas) con su estado de préstamo
    public function listarLaptops(): array
    {
        $sql = "
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

            WHERE l.activa = 1

            ORDER BY l.id
        ";

        $consulta = $this->conexion->query($sql);

        $laptops = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $laptops;
    }

    public function existeModelo(string $modelo): bool
    {
        $consulta = $this->conexion->prepare("SELECT 1 FROM LAPTOP WHERE modelo = :modelo AND activa = 1 LIMIT 1");
        $consulta->execute(["modelo" => $modelo]);

        $existe = $consulta->fetchColumn() !== false;

        $consulta = null;

        return $existe;
    }

    public function registrarLaptop(string $modelo): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sqlLaptop = "INSERT INTO LAPTOP (modelo) VALUES (:modelo)";
            $consultaLaptop = $this->conexion->prepare($sqlLaptop);
            $consultaLaptop->execute(["modelo" => $modelo]);
            $idLaptop = (int) $this->conexion->lastInsertId();

            $this->conexion->commit();
            return true;

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }

    /**
     * Devuelve: "ok", "noExiste" o "prestada".
     */
    public function darDeBajaLaptop(int $idLaptop): string
    {
        try {
            $this->conexion->beginTransaction();

            $sqlLaptop = "SELECT id FROM LAPTOP WHERE id = :idLaptop AND activa = 1 FOR UPDATE";
            $consultaLaptop = $this->conexion->prepare($sqlLaptop);
            $consultaLaptop->execute(["idLaptop" => $idLaptop]);
            $laptop = $consultaLaptop->fetch(PDO::FETCH_ASSOC);

            if ($laptop === false) {
                $this->conexion->rollBack();
                return "noExiste";
            }

            $sqlAbierto = "SELECT 1 FROM PRESTAMO WHERE idLaptop = :idLaptop AND fechaFin IS NULL";
            $consultaAbierto = $this->conexion->prepare($sqlAbierto);
            $consultaAbierto->execute(["idLaptop" => $idLaptop]);

            if ($consultaAbierto->fetchColumn() !== false) {
                $this->conexion->rollBack();
                return "prestada";
            }

            $sqlBaja = "UPDATE LAPTOP SET activa = 0 WHERE id = :idLaptop";
            $consultaBaja = $this->conexion->prepare($sqlBaja);
            $consultaBaja->execute(["idLaptop" => $idLaptop]);


            $this->conexion->commit();
            return "ok";

        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return "noExiste";
        }
    }
}