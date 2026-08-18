<?php

session_start();
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroEspacio.php";

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

$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

$registroEspacio = new RegistroEspacio($conexion);

$idEspacio = $registroEspacio->registrarEspacio(
    $tipoEspacio, $nroEspacio, $grupo
);

$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idEspacio=" . $idEspacio);
exit;

?>