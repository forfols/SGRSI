<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/IncidenciaDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

class ControladorIncidencia
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
            "GET" => $this->listarIncidencia(),
            "POST" => $this->registrarIncidencia(),
            "PATCH" => $this->modificarIncidencia(),
            "DELETE" => $this->eliminarIncidencia(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    private function listarIncidencia(): void
    {
        $conexion = $this->conectar();
        $dao = new IncidenciaDAO($conexion);
        RespuestaJson::exito($dao->listarIncidencia());
    }

    private function registrarIncidencia(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $tipoEspacio = $datos["tipoEspacio"];
        $tipo = trim($datos["tipo"] ?? "");
        $idEquipo = $datos["nroPc"] ?? null;
        $nombreAlumno = $datos["nombreAlumno"] ?? null;
        $descripcion = $datos["descripcion"] ?? "";
        $idRegistroEspacio = $datos["idRegistroEspacio"] ?? null;

        if ($tipo === "PC" && ($idEquipo === "" || $nombreAlumno === "")) {
            RespuestaJson::error("Existen campos vacíos", 422);
        } else if ($tipo === "" || $descripcion === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        if ($tipo === "Otros") {
            $idEquipo = null;
            $nombreAlumno = null;
        }

        $conexion = $this->conectar();
        $dao = new IncidenciaDAO($conexion);

        $idTipoIncidencia = $dao->registrarTipoIncidencia(
            $tipo,
            $idEquipo,
            $nombreAlumno,
            $descripcion
        );

        $idEstado = $dao->registrarEstado();

        $dao->registrarIncidencia(
            $idRegistroEspacio,
            $idTipoIncidencia,
            $_SESSION["ci"],
            $idEstado
        );

        RespuestaJson::exito(["mensaje" => "Se registró la incidencia"], 201);
    }

    private function modificarIncidencia(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $idIncidencia = trim($datos["idIncidencia"] ?? "");
        $tipoIncidencia = $datos["tipoIncidencia"] ?? "";
        $idEquipo = $datos["nroPc"] ?? null;
        $nombreAlumno = $datos["nombreAlumno"] ?? "";
        $descripcion = $datos["descripcion"] ?? "";

        if ($tipoIncidencia === "PC" && ($idEquipo === "" || $nombreAlumno === "")) {
            RespuestaJson::error("No se pudo modificar la incidencia: Se eligio una incidencia sobre PC pero no se asigno un alumno o pc", 400);
        } else if ($tipoIncidencia === "" || $descripcion === "") {
            RespuestaJson::error("No se pudo modificar la incidencia: hay campos vacíos", 400);
        }

        $conexion = $this->conectar();
        $dao = new IncidenciaDAO($conexion);

        //Verifica el estado actual de la incidencia antes de intentar modificarla
        $incidencia = $dao->verificarEstado($idIncidencia);

        //fetch() devuelve false si no encuentra la incidencia
        if ($incidencia === false) {
            RespuestaJson::error("No se encontró la incidencia", 404);
        }

        $estado = $incidencia["tipo"];
        if ($estado != "Sin asignar") {
            RespuestaJson::error("No se pudo modificar la incidencia: La incidencia está siendo procesada por un Técnico", 409);
        }

        $resultado = $dao->modificarIncidencia(
            $idIncidencia,
            $tipoIncidencia,
            $idEquipo,
            $nombreAlumno,
            $descripcion
        );

        if ($resultado == false) {
            RespuestaJson::error("No se pudo modificar la incidencia", 500);
        }

        RespuestaJson::exito(["mensaje" => "Se ha modificado la incidencia con éxito"]);
    }

    private function eliminarIncidencia(): void
    {
        $this->verificarCsrf();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];
        $idIncidencia = trim($datos["idIncidencia"] ?? "");

        if ($idIncidencia === "") {
            RespuestaJson::error("Falta el id de la incidencia", 422);
        }

        $conexion = $this->conectar();
        $dao = new IncidenciaDAO($conexion);

        //Verifica el estado actual de la incidencia antes de intentar eliminarla
        $incidencia = $dao->verificarEstado($idIncidencia);

        if ($incidencia === false) {
            RespuestaJson::error("No se encontró la incidencia", 404);
        }

        $estado = $incidencia["tipo"];
        if ($estado != "Sin asignar") {
            RespuestaJson::error("No se pudo eliminar la incidencia: La incidencia está siendo procesada por un Técnico", 409);
        }

        $resultado = $dao->eliminarIncidencia($idIncidencia);

        if ($resultado == false) {
            RespuestaJson::error("No se pudo eliminar la incidencia", 500);
        }

        RespuestaJson::exito(["mensaje" => "Se ha eliminado la incidencia"]);
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