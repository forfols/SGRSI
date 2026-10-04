<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/RegistroEspacioController.php";

session_start();

$controlador = new RegistroEspacioController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);