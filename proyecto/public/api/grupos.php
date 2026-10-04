<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/GrupoController.php";

session_start();

$controlador = new GrupoController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);