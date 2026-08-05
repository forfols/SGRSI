<?php
session_start();

if (!isset($_SESSION["ci"])) {
    $mensaje = "Acceso Denegado: Sesión no iniciada";
    header("Location: inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

if (!isset($_SESSION["solicitante"]) || $_SESSION["solicitante"] != true) {
header("Location: ../app/controlador/cerrarSesion.php?motivo=rol");
    exit;
}

require_once __DIR__ . "/../app/vista/solicitanteRegistroEspacio.php";
?>  