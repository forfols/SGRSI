<?php

session_start();
require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["ci"])) {
    http_response_code(401); // No hay sesión
    header("Location: /GitHub/ramaAlexander/proyecto/public/cerrarSesion.php?motivo=sinSesion");
    exit;
}
// if (empty($_SESSION["solicitante"]) || empty($_SESSION["tecnico"]) || empty($_SESSION["administrador"])) {
//     header("Location: cerrarSesion.php?motivo=rol");
//     exit;
// }


require_once RUTA_VISTA . "/indexGeneral.php";

?>