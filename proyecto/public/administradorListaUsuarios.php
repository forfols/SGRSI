<?php
session_start();

if (!isset($_SESSION["ci"])) {
    $mensaje = "Acceso Denegado: Sesión no iniciada";
    header("Location: inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

if ( !isset($_SESSION["rol"]) || $_SESSION["rol"] !== "Administrador" ) {
    $mensaje = "Acceso Denegado: Rol incorrecto";
    header("Location: inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

require_once __DIR__ . "/../app/vista/administradorListaUsuarios.php";
?>  