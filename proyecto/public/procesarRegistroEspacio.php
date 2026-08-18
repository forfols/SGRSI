<?php
require_once __DIR__ . "/../config/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: indexSolicitante.php"); // o la vista que corresponda
    exit;
}
require_once RUTA_CONTROLADOR . "/procesarRegistroEspacio.php";
?>