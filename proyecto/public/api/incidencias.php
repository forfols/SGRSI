<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/IncidenciaController.php";

session_start();

$controlador = new IncidenciaController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);