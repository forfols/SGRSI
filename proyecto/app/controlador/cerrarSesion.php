<?php

/**
 * Controlador encargado de cerrar la sesión del usuario.
 * Marca al usuario como inactivo en la base de datos, destruye la sesión actual
 * y, según el motivo recibido por GET, define el mensaje de error que se mostrará
 * en la pantalla de inicio de sesión.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();


//Si la conexión falló, se informa el error y se redirige al inicio de sesión
if ($conexion === null) {
    http_response_code(500);
    session_start();
    $_SESSION["error"] = "Acceso Denegado: No se pudo establecer conexión con la base de datos";
    header("Location:" . URL_PUBLIC . "/inicioSesion.php");
    exit;
}

$accesoDatosUsuario = new AccesoDatosUsuario($conexion);


//Si había una sesión activa, marca al usuario como inactivo antes de destruirla
if (isset($_SESSION["ci"])) {
    $accesoDatosUsuario->estaActivo($_SESSION["ci"], false);
}

$conectorPDO->desconectar();

$motivo = $_GET["motivo"] ?? "";

//Limpia todos los datos de la sesión actual
$_SESSION = [];
session_destroy();


//Según el motivo recibido, define el mensaje de error a mostrar en el inicio de sesión
if ($motivo == "token") {
    session_start();
    $_SESSION["error"] = "Acceso Denegado";
} else if($motivo == "peticionIncorrecta"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Petición incorrecta";
}else if($motivo == "sinSesion"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Sesión no iniciada";
}else if ($motivo == "rol") {
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Rol incorrecto";
}else if ($motivo == "credenciales"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: Cédula o contraseña incorrectos";
}else if ($motivo == "sinConexion"){
    session_start();
    $_SESSION["error"] = "Acceso Denegado: No se pudo establecer conexión con la base de datos";
}


//Redirige siempre a la pantalla de inicio de sesión
header("Location:" . URL_PUBLIC . "/inicioSesion.php");
exit;