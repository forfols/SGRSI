<?php

/**
 * @file cargarIncidenciasTecnico.php
 * @brief Controlador encargado de cargar el listado de incidencias para la vista del técnico.
 *
 * Establece la conexión a la base de datos, recupera todas las incidencias registradas
 * y delega la presentación a la vista correspondiente.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroIncidencia.php";


//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    //Recupera el listado completo de incidencias con sus datos asociados
    $registroIncidencia = new RegistroIncidencia($conexion);
    $incidencias = $registroIncidencia->listarIncidencias();

    //print_r($incidencias);
    //exit;
$conectorPDO->desconectar();


//Incluye la vista del técnico, que recibe el arreglo $incidencias
require_once RUTA_VISTA . "/tecnico.php";