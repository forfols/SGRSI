<?php


session_start();

if (!isset($_SESSION["ci"])) {
    header("Location: inicioSesion.php?error=sinSesionIniciada");
    exit;
}

require_once __DIR__ . "/../modelo/ConectorPDO.php";
require_once __DIR__ . "/../modelo/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO("localhost:3306", "root", "", "SGRSI");
$conexion = $conectorPDO->establecerConexion();

$accesoDatosUsuario = new AccesoDatosUsuario($conexion);


$accesoDatosUsuario->estaActivo($_SESSION["ci"], false);

$conectorPDO->desconectar();

$motivo = $_GET["motivo"] ?? "";

$_SESSION = [];
session_destroy();

if ($motivo == "rol") {
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Rol incorrecto";
}


header("Location: ../../public/inicioSesion.php");
exit;