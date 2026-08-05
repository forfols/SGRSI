<?php

session_start();

if (!isset($_SESSION["ci"])) {
    $_SESSION["motivoError"]= "sinSesion";
    header("Location: cerrarSesion.php");
    exit;
}

if (!isset($_SESSION["tecnico"]) || $_SESSION["tecnico"] != true) {
    $_SESSION["motivoError"]= "rol";
    header("Location: cerrarSesion.php");
    exit;
}

require_once __DIR__ . "/../app/vista/tecnico.php";

?>