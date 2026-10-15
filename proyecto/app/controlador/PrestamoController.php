<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/PrestamoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class PrestamoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRol("tecnico");

        //El token solo se exige en las operaciones que modifican datos
        if (in_array($metodo, ["POST", "PUT"], true)) {
            Token::verificarCSRF();
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->devolver(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listar(): void
    {
        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);

        //Menú desplegable del formulario (?disponibles=1): solo las laptops que no están prestadas
        if (isset($_GET["disponibles"])) {
            $datos = $dao->listarLaptopsDisponibles();
        } else {
            $datos = $dao->listarPrestamos();
        }

        Conexion::desconectar();

        RespuestaJson::exito($datos);
    }

    private function alta(): void
    {
        $datos = $this->leerJson();

        $nombre = trim($datos["nombre"] ?? "");
        $apellido = trim($datos["apellido"] ?? "");
        $ciAlumno = trim($datos["cedula"] ?? "");
        $idLaptop = (int) ($datos["idLaptop"] ?? 0);

        if ($nombre === "" || $apellido === "" || $ciAlumno === "" || $idLaptop === 0) {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $ciAlumno)) {
            RespuestaJson::error("Cédula incorrecta", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);
        $resultado = $dao->registrarPrestamo($nombre . " " . $apellido, $ciAlumno, $idLaptop, (string) $_SESSION["ci"]);
        Conexion::desconectar();

        if (!$resultado) {
            RespuestaJson::error("La laptop no está disponible", 409);
        }

        RespuestaJson::exito(["mensaje" => "Se registró el préstamo"], 201);
    }

    private function devolver(): void
    {
        $datos = $this->leerJson();

        $idPrestamo = (int) ($datos["idPrestamo"] ?? 0);

        if ($idPrestamo === 0) {
            RespuestaJson::error("Falta el id del préstamo", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new PrestamoDAO($conexion);
        $prestamo = $dao->buscarPrestamo($idPrestamo);

        if ($prestamo === null) {
            Conexion::desconectar();
            RespuestaJson::error("No se encontró el préstamo", 404);
        }
        if ($prestamo["fechaFin"] !== null) {
            Conexion::desconectar();
            RespuestaJson::error("La laptop ya fue devuelta", 409);
        }

        $resultado = $dao->devolverPrestamo($idPrestamo);
        Conexion::desconectar();

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar la devolución", 400);
        }

        RespuestaJson::exito(["mensaje" => "Se registró la devolución"]);
    }

    private function leerJson(): array
    {
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        return $datos;
    }
}