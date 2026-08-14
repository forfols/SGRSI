<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/RegistroEspacio.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
   //$mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$tipoEspacio = trim($_POST["tipoEspacio"] ?? "");
$nroEspacio = $_POST["nroEspacio"] ?? "";
$grupo = $_POST["grupo"] ?? "";

$conectorPDO = new ConectorPDO ("localhost:3306", "root", "", "SGRSI");
$conexion = $conectorPDO->establecerConexion();

$registroEspacio = new RegistroEspacio($conexion);

$idEspacio = $registroEspacio->registrarEspacio(
    $tipoEspacio, $nroEspacio, $grupo
);

$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idEspacio=" . $idEspacio);
exit;

?>