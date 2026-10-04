<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/EquipoController.php";

session_start();

$controlador = new EquipoController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);