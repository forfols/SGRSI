<?php
    session_start();
require_once __DIR__ . "/../config/config.php";

    if ($_SESSION["motivoError"]=="sinSesion"){
        header("Location: /GitHub/ramaAlexander/proyecto/app/controlador/cerrarSesion.php?motivo=". $_SESSION["motivoError"]);
    exit;
    }
    if ($_SESSION["motivoError"]=="rol"){
        header("Location: /GitHub/ramaAlexander/proyecto/app/controlador/cerrarSesion.php?motivo=". $_SESSION["motivoError"]);
    exit;
    }



require_once RUTA_CONTROLADOR . "/cerrarSesion.php";
?>