<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/ControladorIncidencia.php";

session_start();

$controlador = new ControladorIncidencia();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);