<?php


require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroEspacio.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario

$tipoEspacio = trim($_POST["tipoEspacio"] ?? "");
$nroEspacio = $_POST["nroEspacio"] ?? "";
$grupo = $_POST["grupo"] ?? "";

$csrfToken = $_POST["csrfToken"];

if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

if ($tipoEspacio === "" || $nroEspacio === "" || $grupo === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo registrar el espacio: hay campos vacíos";
    header("Location: solicitanteRegistroEspacio");
    exit;
}


$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$registroEspacio = new RegistroEspacio($conexion);

$idRegistroEspacio = $registroEspacio->registrarEspacio(
    $tipoEspacio,
    $nroEspacio,
    $grupo
);


$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio . "&tipoEspacio=". $tipoEspacio);
exit;

?>