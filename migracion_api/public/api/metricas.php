<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/MetricaController.php";

session_start();

$controlador = new MetricaController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);