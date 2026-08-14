<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/RegistroTipoIncidencia.php";
require_once __DIR__ . "/../modelo/RegistroIncidencia.php";

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

$conectorPDO = new ConectorPDO ("localhost:3306", "root", "", "SGRSI");
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