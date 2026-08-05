<?php
    session_start();

    if ($_SESSION["motivoError"]=="sinSesion"){
        header("Location: /GitHub/ramaAlexander/proyecto/app/controlador/cerrarSesion.php?motivo=". $_SESSION["motivoError"]);
    exit;
    }
    if ($_SESSION["motivoError"]=="rol"){
        header("Location: /GitHub/ramaAlexander/proyecto/app/controlador/cerrarSesion.php?motivo=". $_SESSION["motivoError"]);
    exit;
    }



    require_once __DIR__ . "/../app/controlador/cerrarSesion.php";
?>