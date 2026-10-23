<?php
require_once __DIR__ . "/../config/config.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

if (!isset($_GET["ruta"])) {
    header("Location: ./index.html");
    exit;
}

try {
    $ruta = $_GET["ruta"];
    $metodo = $_SERVER["REQUEST_METHOD"];

    if (!is_string($ruta) || trim($ruta) === "") {
        RespuestaJson::error("La ruta es inválida", 400);
    }

    //No hay session_start(): el servidor no conserva estado entre peticiones
    require RUTA_APP . "/rutas/api.php";
} catch (Throwable $error) {
    error_log((string) $error);
    RespuestaJson::error("Error interno del servidor", 500);
}