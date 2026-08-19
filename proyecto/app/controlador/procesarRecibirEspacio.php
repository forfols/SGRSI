<?php


require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RecibirEspacio.php";


$conectorPDO = new ConectorPDO(
    $_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'],
    $_ENV['DB_USUARIO'],
    $_ENV['DB_CLAVE'],
    $_ENV['DB_NOMBRE']
);

$conexion = $conectorPDO->establecerConexion();

$recibirEspacio = new RecibirEspacio($conexion);

$espacios = $recibirEspacio->recibirEspacios();
$grupos = $recibirEspacio->recibirGrupos();

$conectorPDO->desconectar();

?>