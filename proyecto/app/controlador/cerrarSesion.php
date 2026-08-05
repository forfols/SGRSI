<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO("localhost:3306", "root", "", "SGRSI");
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


header("Location: /GitHub/ramaAlexander/proyecto/public/inicioSesion.php");
exit;