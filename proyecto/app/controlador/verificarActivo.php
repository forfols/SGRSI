<?php


require_once __DIR__ . "/../../config/config.php";

$motivo = $_GET["motivo"] ?? "";

if($motivo == "sesionActiva"){
    http_response_code(409);
    $_SESSION["error"] = "Acceso Denegado: La sesión está activa en otro dispositivo";
}

header("Location:" . URL_PUBLIC . "/inicioSesion.php");
exit;