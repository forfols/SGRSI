<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/LaptopDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class LaptopController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRol("tecnico");

        //El token solo se exige en las operaciones que modifican datos
        if (in_array($metodo, ["POST", "PUT"], true)) {
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->baja(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listar(): void
    {
        $conexion = Conexion::conectar();
        $laptops = (new LaptopDAO($conexion))->listarLaptops();
        Conexion::desconectar();

        RespuestaJson::exito($laptops);
    }

    private function alta(): void
    {
        $datos = $this->leerJson();

        $modelo = trim($datos["modelo"] ?? "");

        if ($modelo === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (strlen($modelo) > 255) {
            RespuestaJson::error("El nombre es demasiado largo", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new LaptopDAO($conexion);

        if ($dao->existeModelo($modelo)) {
            Conexion::desconectar();
            RespuestaJson::error("Ya existe una laptop con ese nombre", 409);
        }

        $resultado = $dao->registrarLaptop($modelo);
        Conexion::desconectar();

        if (!$resultado) {
            RespuestaJson::error("No se pudo agregar la laptop", 400);
        }

        RespuestaJson::exito(["mensaje" => "Se agregó la laptop"], 201);
    }

    private function baja(): void
    {
        $datos = $this->leerJson();

        $idLaptop = (int) ($datos["idLaptop"] ?? 0);
        $motivo = trim($datos["motivo"] ?? "");

        if ($idLaptop === 0) {
            RespuestaJson::error("No se seleccionó ninguna laptop", 422);
        }

        $conexion = Conexion::conectar();
        $resultado = (new LaptopDAO($conexion))->darDeBajaLaptop($idLaptop);
        Conexion::desconectar();

        if ($resultado === "prestada") {
            RespuestaJson::error("La laptop está prestada: primero hay que devolverla", 409);
        }
        if ($resultado === "noExiste") {
            RespuestaJson::error("No se encontró la laptop", 404);
        }

        RespuestaJson::exito(["mensaje" => "Se dio de baja la laptop"]);
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