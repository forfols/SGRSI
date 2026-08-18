<?php

session_start();
require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["ci"])) {
    header("Location: /GitHub/ramaAlexander/proyecto/public/cerrarSesion.php?motivo=sinSesion");
    exit;
}
if (!isset($_SESSION["ci"])) {
    http_response_code(401); // No hay sesión 
    $_SESSION["motivoError"] = "sinSesion";
    header("Location: cerrarSesion.php");
    exit;
}

if (!isset($_SESSION["administrador"]) || $_SESSION["administrador"] != true) {
    http_response_code(403); // Hay sesión, pero no tiene el rol 
    $_SESSION["motivoError"] = "rol";
    header("Location: cerrarSesion.php");
    exit;
}
// if (empty($_SESSION["solicitante"]) || empty($_SESSION["tecnico"]) || empty($_SESSION["administrador"])) {
//     header("Location: cerrarSesion.php?motivo=rol");
//     exit;
// }


require_once RUTA_VISTA . "/indexGeneral.php";

?>