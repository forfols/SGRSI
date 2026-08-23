<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroIncidencia.php";



$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    $registroIncidencia = new RegistroIncidencia($conexion);
    $incidencias = $registroIncidencia->listarIncidencias();

    //print_r($incidencias);
    //exit;
$conectorPDO->desconectar();



require_once RUTA_VISTA . "/administradorListaIncidencias.php";
