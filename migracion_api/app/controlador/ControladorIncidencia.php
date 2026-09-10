// app/controlador/UsuarioController.php
<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/IncidenciaDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_MODELO . "/EliminarIncidencia.php";

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

        //https://www.w3schools.com/php/php_match.asp
        match ($metodo) {
            "GET" => $this->listarIncidencia(),
            "POST" => $this->registrarIncidencia(),
            //PONER PUT
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
        $this->registrarIncidencia();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $tipoEspacio = $datos["tipoEspacio"];
        $tipo = trim($datos["tipo"] ?? "");
        $idEquipo = $datos["nroPc"] ?? null;
        $nombreAlumno = $datos["nombreAlumno"] ?? null;
        $descripcion = $datos["descripcion"] ?? "";
        $idRegistroEspacio = $datos["idRegistroEspacio"] ?? null;

        //Valida que, si la incidencia es sobre una PC, se hayan indicado el equipo y el alumno
        if ($tipo === "PC" && ($idEquipo === "" || $nombreAlumno === "")) {
            RespuestaJson::error("Existen campos vacíos", 422);
        } else if ($tipo === "" || $descripcion === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        //Si la incidencia no es sobre una PC, se descartan el equipo y el alumno
        if ($tipo === "Otros") {
            $idEquipo = null;
            $nombreAlumno = null;
        }

        //Establece la conexión a la base de datos utilizando las credenciales del entorno
        $conexion = $this->conectar();
        $dao = new IncidenciaDAO($conexion);

        $idTipoIncidencia = $dao->registrarTipoIncidencia(
            $tipo,
            $idEquipo,
            $nombreAlumno,
            $descripcion
        );


        //Registra el estado inicial de la incidencia, con valores por defecto
        $idEstado = $dao->registrarEstado();

        //Registra la incidencia vinculando el espacio, el tipo, el solicitante y el estado creados
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
        $this->modificarIncidencia();

        $datos = json_decode(file_get_contents("php://input"), true) ?? [];

        $idIncidencia = trim($datos["idIncidencia"] ?? "");
        $estado = trim($datos["estadoIncidencia"] ?? "");

        $tipoIncidencia = $datos["tipoIncidencia"] ?? "";
        $idEquipo = $datos["nroPc"] ?? null;
        $nombreAlumno = $datos["nombreAlumno"] ?? "";
        $descripcion = $datos["descripcion"] ?? "";

    
        //Valida que, si la incidencia es sobre una PC, se hayan indicado el equipo y el alumno
        if ($tipoIncidencia === "PC" && ($nroPc === "" || $nombreAlumno === "")) {
            http_response_code(400);
            $_SESSION["error"] = "No se pudo modificar la incidencia: Se eligio una incidencia sobre PC pero no se asigno un alumno o pc";
            RespuestaJson::exito("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
        } else if ($tipoIncidencia === "" || $descripcion === "") {
            //Valida que los campos obligatorios hayan sido completados
            http_response_code(400);
            $_SESSION["error"] = "No se pudo modificar la incidencia: hay campos vacíos";
            RespuestaJson::exito("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
        }

        //Establece la conexión a la base de datos utilizando las credenciales del entorno
        $conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
        $conexion = $conectorPDO->establecerConexion();

        //Si la conexión falló, se cierra la sesión indicando el motivo
        if ($conexion === null) {
            http_response_code(500);
            RespuestaJson::exito("Location: cerrarSesion.php?motivo=sinConexion");
        }


        //Verifica el estado actual de la incidencia antes de intentar modificarla
        $verificarEstado = new VerificarEstado($conexion);
        $incidencia = $verificarEstado->verificarEstado($idIncidencia);

        //Si la incidencia ya está siendo procesada por un técnico, no se permite modificarla
        $estado = $incidencia["tipo"];
        if ($estado != "Sin asignar") {
            http_response_code(409);
            $_SESSION["error"] = "No se pudo modificar la incidencia: La incidencia está siendo procesada por un Técnico";
            RespuestaJson::exito("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
        }

        //Modifica los datos de la incidencia con la información recibida
        $modificarIncidencia = new ModificarIncidencia($conexion);

        $resultado = $modificarIncidencia->modificarIncidencia(

            $idIncidencia,
            $tipoIncidencia,
            $idEquipo,
            $nombreAlumno,
            $descripcion
        );

        $conectorPDO->desconectar();

        //Si la modificación falló, se informa el error
        if ($resultado == false) {
            http_response_code(500);
            $_SESSION["error"] = "No se pudo modificar la incidencia";
            RespuestaJson::exito("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
        }

        //Si todo salió bien, se informa el éxito de la operación
        $_SESSION["mensaje"] = "Se ha modificado la incidencia con éxito";
        RespuestaJson::exito("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    }

    private function eliminarIncidencia(): void
    {
        //Comprueba que la solicitud haya sido enviada mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}


//Recupera los datos provenientes del formulario
$idIncidencia = trim($_POST["idIncidencia"] ?? "");
$estado= trim($_POST["estadoIncidencia"] ??"");
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}


//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

    //Si la conexión falló, se cierra la sesión indicando el motivo
    if ($conexion === null) {
        http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

//Verifica el estado actual de la incidencia antes de intentar eliminarla
$verificarEstado = new VerificarEstado($conexion);
$incidencia = $verificarEstado->verificarEstado(
    $idIncidencia
);

//Si la incidencia ya está siendo procesada por un técnico, no se permite eliminarla
$estado = $incidencia["tipo"];
if ($estado != "Sin asignar") {
    $_SESSION["error"] = "No se pudo eliminar la incidencia: La incidencia está siendo procesada por un Técnico";
    header("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    exit;
}

//Elimina la incidencia junto con sus registros asociados
$eliminarIncidencia = new EliminarIncidencia($conexion);

    $resultado = $eliminarIncidencia->eliminarIncidencia(

    $idIncidencia,
);

$conectorPDO->desconectar();

//Si la eliminación falló, se informa el error
if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo eliminar la incidencia";
    RespuestaJson::error("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
}

    //Si todo salió bien, se informa el éxito de la operación
    $_SESSION["mensaje"] = "Se ha eliminado la incidencia";
    RespuestaJson::error("Location: " . URL_CONTROLADOR . "/cargarIncidenciasSolicitante.php");
    }

}
