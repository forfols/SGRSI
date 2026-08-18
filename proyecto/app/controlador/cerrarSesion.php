<?php

session_start();
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

$accesoDatosUsuario = new AccesoDatosUsuario($conexion);


if (isset($_SESSION["ci"])) {
    $accesoDatosUsuario->estaActivo($_SESSION["ci"], false);
}

$conectorPDO->desconectar();

$motivo = $_GET["motivo"] ?? "";

$_SESSION = [];
session_destroy();

if($motivo == "peticionIncorrecta"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Petición incorrecta";
}else if($motivo == "sinSesion"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Sesión no iniciada";
}else if ($motivo == "rol") {
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Rol incorrecto";
}else if ($motivo == "credenciales"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Cédula o contraseña incorrectos";
}/*else if($motivo == "sesionActiva"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: La sesión está activa en otro dispositivo";
    header("Location: ../public/inicioSesion.php");
    exit;
}
    */


header("Location:" . URL_PUBLIC . "/inicioSesion.php");
exit;