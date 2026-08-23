<?php


require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroUsuario.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$nombre = trim($_POST["nombre"] ?? "");
$apellido = $_POST["apellido"] ?? "";
$ci = $_POST["ci"] ?? "";
$contra = $_POST["contra"] ?? "";
$repetirContra = $_POST["repetirContra"] ?? "";
$solicitante = $_POST["solicitante"] ?? NULL;
$tecnico = $_POST["tecnico"] ?? NULL;
$administrador = $_POST["administrador"] ?? NULL;
$csrfToken = $_POST["csrfToken"];

if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

if ($contra !== $repetirContra) {
    http_response_code(400);
    $_SESSION["error"] = "Las contraseñas no coinciden";
    header("Location: " . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}
$contraHash = password_hash($contra, PASSWORD_DEFAULT);

$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$registroUsuario = new RegistroUsuario($conexion);

if ($registroUsuario->existeUsuario($ci)) {
    $_SESSION["error"] ="Un usuario con esa cédula ya existe";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}

$nombreCompleto = $nombre . " " . $apellido;

$seCreo = $registroUsuario->registrarUsuario(
    $ci,
    $contraHash,
    $nombreCompleto,
    $solicitante,
    $tecnico,
    $administrador
);

if($seCreo== true){
    $_SESSION["mensaje"] ="Se creó el usuario correctamente";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
} else {
    http_response_code(500);
    $_SESSION["error"] ="Hubo un error al crear el usuario";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}


$nombreCompleto = $nombre + " " +$apellido;


$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

$registroEspacio = new RegistroEspacio($conexion);

$idRegistroEspacio = $registroEspacio->registrarEspacio(
    $tipoEspacio,
    $nroEspacio,
    $grupo
);


$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio);
exit;

?>