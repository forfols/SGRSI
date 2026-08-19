<?php
require_once __DIR__ . "/../config/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Método no permitido
    header("Location: indexSolicitante.php");
    exit;
}

require_once RUTA_CONTROLADOR . "/procesarRegistroEspacio.php";
?>