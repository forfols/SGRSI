<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/LaptopController.php";

session_start();

$controlador = new LaptopController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);