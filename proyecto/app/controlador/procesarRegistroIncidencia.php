<?php

session_start();
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroTipoIncidencia.php";
require_once RUTA_MODELO . "/RegistroIncidencia.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
   //$mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$tipo = trim($_POST["tipo"] ?? "");
$nroPc = $_POST["nroPc"] ?? null;
$nombreAlumno = $_POST["nombreAlumno"] ?? null;
$descripcion = $_POST["descripcion"] ?? "";
$idEspacio = $_POST["idEspacio"] ?? null;

$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

$registroTipoIncidencia = new RegistroTipoIncidencia($conexion);

$idTipoIncidencia = $registroTipoIncidencia->registrarTipoIncidencia(
    $tipo, $nroPc, $nombreAlumno, $descripcion
);



$registroIncidencia = new RegistroIncidencia($conexion);


$registroIncidencia->registrarIncidencia(
    $idEspacio, $idTipoIncidencia, $_SESSION["ci"], $_SESSION["nombre"]
);


$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idEspacio=" . $idEspacio);
exit;

?>