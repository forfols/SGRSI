<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarUsuario.php";



//Recupera los datos enviados por el formulario
$ci = trim($_POST["ci"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$solicitante = trim($_POST["solicitante"] ?? "");
$tecnico = $_POST["tecnico"] ?? "";
$administrador = $_POST["administrador"] ?? "";


//Sección que valida los datos recibidos del formulario
if ($ci === "" || $nombre === "") {
    $_SESSION["error"] = "No se pudo crear el usuario: hay campos vacíos";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

    if ($conexion === null) {
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
    $_SESSION["error"] = "No se pudo modificar el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;
}

    $_SESSION["mensaje"] = "Se ha modificado el usuario";
    header("Location: " . URL_CONTROLADOR . "/cargarUsuarios.php");
    exit;