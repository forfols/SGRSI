<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/PrestamoController.php";

session_start();

$controlador = new PrestamoController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);