<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarUsuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera los datos enviados por el formulario
$ci = trim($_POST["ci"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$solicitante = trim($_POST["solicitante"] ?? "");
$tecnico = $_POST["tecnico"] ?? "";
$administrador = $_POST["administrador"] ?? "";
$csrfToken = $_POST["csrfToken"];
$activo= $_POST["estaActivo"] ??"";

if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Sección que valida los datos recibidos del formulario
if ($ci === "" || $nombre === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo modificar el usuario: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

if ($activo != 0){
    http_response_code(409);
    $_SESSION["error"] = "No se pudo modificar el usuario: el usuario tiene una sesión activa";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

    if ($conexion === null) {
        http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$modificarUsuario = new ModificarUsuario($conexion);

    $resultado = $modificarUsuario->modificarUsuario(

    $ci,
    $nombre,
    $solicitante,
    $tecnico,
    $administrador
);

$conectorPDO->desconectar();

if ($resultado == false) {
    http_response_code(500);
    $_SESSION["error"] = "No se pudo modificar el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

    $_SESSION["mensaje"] = "Se ha modificado el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;