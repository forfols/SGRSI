<?php

session_start();

$motivo = $_GET["motivo"] ?? "";

if($motivo == "sesionActiva"){
    $_SESSION["error"] = "Acceso Denegado: La sesión está activa en otro dispositivo";
}

header("Location: ../../public/inicioSesion.php");
exit;