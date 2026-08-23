<?php

/**
 * @file cargarUsuarios.php
 * @brief Controlador encargado de cargar el listado de usuarios para la vista del administrador.
 *
 * Establece la conexión a la base de datos, recupera todos los usuarios con sus roles
 * y delega la presentación a la vista correspondiente.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    //Recupera el listado completo de usuarios junto con sus roles
    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $usuarios = $accesoDatosUsuario->listarUsuarios();

$conectorPDO->desconectar();

//A diferencia de otros casos, acá se utiliza require_once en vez de header porque se le incluye la lista de usuarios
require_once RUTA_VISTA . "/administradorListaUsuarios.php";