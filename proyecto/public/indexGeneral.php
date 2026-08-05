<?php

session_start();

if (!isset($_SESSION["ci"])) {
    $mensaje = "Acceso Denegado: Sesión no iniciada";
    header("Location: inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

require_once __DIR__ . "/../app/vista/indexGeneral.php";

?>