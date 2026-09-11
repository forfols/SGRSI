<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/ControladorEspacio.php";

session_start();

$controlador = new ControladorEspacio();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);