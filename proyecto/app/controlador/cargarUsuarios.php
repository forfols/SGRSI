<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

$conectorPDO = new ConectorPDO($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $usuarios = $accesoDatosUsuario->listarUsuarios();

$conectorPDO->desconectar();

//A diferencia de otros casos, acá se utiliza require_once en vez de header porque se le incluye la lista de usuarios
require_once RUTA_VISTA . "/administradorListaUsuarios.php";
