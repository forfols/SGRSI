<?php

/**
 * @file procesarRecibirEquipo.php
 * @brief Controlador encargado de recuperar el listado de equipos registrados.
 *
 * Utilizado para poblar los formularios que requieren seleccionar un equipo.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RecibirEquipo.php";


//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);

$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}
//Recupera todos los equipos registrados en el sistema
$recibirEquipo = new RecibirEquipo($conexion);

$equipos = $recibirEquipo->recibirEquipos();

//var_dump($equipos);
//exit;


$conectorPDO->desconectar();

?>