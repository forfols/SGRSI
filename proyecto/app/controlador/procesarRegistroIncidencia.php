<?php


require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroTipoIncidencia.php";
require_once RUTA_MODELO . "/RegistroIncidencia.php";
require_once RUTA_MODELO . "/RegistroEstado.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
   //$mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$tipoEspacio = $_POST["tipoEspacio"];

$tipo = trim($_POST["tipo"] ?? "");
$idEquipo = $_POST["nroPc"] ?? null;
$nombreAlumno = $_POST["nombreAlumno"] ?? null;
$descripcion = $_POST["descripcion"] ?? "";
$idRegistroEspacio = $_POST["idRegistroEspacio"] ?? null;


$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$registroTipoIncidencia = new RegistroTipoIncidencia($conexion);

$idTipoIncidencia = $registroTipoIncidencia->registrarTipoIncidencia(
    $tipo, $idEquipo, $nombreAlumno, $descripcion
);

$registroEstado = new RegistroEstado($conexion);
$idEstado = $registroEstado->registrarEstado();

$registroIncidencia = new RegistroIncidencia($conexion);
$registroIncidencia->registrarIncidencia(
    $idRegistroEspacio,
    $idTipoIncidencia,
    $_SESSION["ci"],
    $idEstado
);


$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio . "&tipoEspacio=". $tipoEspacio);
exit;

?>