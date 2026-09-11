// app/controlador/ControladorEspacio.php
<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EspacioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

class ControladorEspacio
{

    public function gestionar(string $metodo): void
    {
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["administrador"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PATCH" => $this->modificar(),
            "DELETE" => $this->baja(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listar(): void
    {
        $conexion = $this->conectar();
        $dao = new EspacioDAO($conexion);
        RespuestaJson::exito($dao->listarEspacios());
    }

    private function alta(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $tipo = trim($datos["tipo"] ?? "");
        $numero = trim($datos["numero"] ?? "");

        if ($tipo === "" || $numero === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        $conexion = $this->conectar();
        $dao = new EspacioDAO($conexion);
        $resultado = $dao->registrarEspacio($tipo, $numero);

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el espacio", 400);
        }

        RespuestaJson::exito(["mensaje" => "Espacio registrado exitosamente"], 201);
    }

    private function modificar(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $id = $datos["id"] ?? null;
        $tipo = trim($datos["tipo"] ?? "");
        $numero = trim($datos["numero"] ?? "");

        if ($id === null || $tipo === "" || $numero === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        $conexion = $this->conectar();
        $dao = new EspacioDAO($conexion);

        $espacio = $dao->buscarEspacio((int) $id);
        if ($espacio === false) {
            RespuestaJson::error("No se encontró el espacio", 404);
        }

        $resultado = $dao->modificarEspacio((int) $id, $tipo, $numero);

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar el espacio", 400);
        }

        RespuestaJson::exito(["mensaje" => "Espacio modificado exitosamente"]);
    }

    private function baja(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];
        $id = $datos["id"] ?? null;

        if ($id === null) {
            RespuestaJson::error("Falta el id del espacio", 422);
        }

        $conexion = $this->conectar();
        $dao = new EspacioDAO($conexion);

        $espacio = $dao->buscarEspacio((int) $id);
        if ($espacio === false) {
            RespuestaJson::error("No se encontró el espacio", 404);
        }

        $resultado = $dao->eliminarEspacio((int) $id);

        if (!$resultado) {
            RespuestaJson::error("No se pudo eliminar el espacio", 400);
        }

        RespuestaJson::exito(["mensaje" => "Espacio eliminado exitosamente"]);
    }

    private function verificarCsrf(): void
    {
        $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!isset($_SESSION["csrfToken"]) || !hash_equals($_SESSION["csrfToken"], $token)) {
            RespuestaJson::error("Solicitud rechazada", 403);
        }
    }

    private function conectar(): PDO
    {
        $conector = new ConectorPDO($_ENV["DB_HOST"] . ":" . $_ENV["DB_PUERTO"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
        $conexion = $conector->establecerConexion();
        if ($conexion === null) {
            RespuestaJson::error("Error de conexión con la base de datos", 500);
        }
        return $conexion;
    }
}