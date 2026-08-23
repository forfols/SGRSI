<?php

/**
 * Controlador encargado de procesar el registro de un nuevo usuario por parte del administrador.
 * Valida la petición y los campos recibidos, hashea la contraseña y delega la creación
 * del usuario en RegistroUsuario.
 */

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroUsuario.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$nombre = trim($_POST["nombre"] ?? "");
$apellido = $_POST["apellido"] ?? "";
$ci = $_POST["ci"] ?? "";
$contra = $_POST["contra"] ?? "";
$repetirContra = $_POST["repetirContra"] ?? "";
$solicitante = $_POST["solicitante"] ?? NULL;
$tecnico = $_POST["tecnico"] ?? NULL;
$administrador = $_POST["administrador"] ?? NULL;
$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Valida que la contraseña y su repetición coincidan
if ($contra !== $repetirContra) {
    http_response_code(400);
    $_SESSION["error"] = "Las contraseñas no coinciden";
    header("Location: " . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}
//Genera el hash de la contraseña antes de almacenarla
$contraHash = password_hash($contra, PASSWORD_DEFAULT);

//Establece la conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Si la conexión falló, se cierra la sesión indicando el motivo
if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

$registroUsuario = new RegistroUsuario($conexion);

//Verifica que no exista previamente un usuario con la misma cédula
if ($registroUsuario->existeUsuario($ci)) {
    $_SESSION["error"] ="Un usuario con esa cédula ya existe";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}

//Arma el nombre completo a partir del nombre y el apellido
$nombreCompleto = $nombre . " " . $apellido;

//Registra el nuevo usuario junto con los roles seleccionados
$seCreo = $registroUsuario->registrarUsuario(
    $ci,
    $contraHash,
    $nombreCompleto,
    $solicitante,
    $tecnico,
    $administrador
);

//Informa el resultado de la creación del usuario
if($seCreo== true){
    $_SESSION["mensaje"] ="Se creó el usuario correctamente";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
} else {
    http_response_code(500);
    $_SESSION["error"] ="Hubo un error al crear el usuario";
    header("Location:" . URL_PUBLIC . "/administradorCrearUsuario.php");
    exit;
}


$nombreCompleto = $nombre + " " +$apellido;


//Establece una nueva conexión a la base de datos utilizando las credenciales del entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

//Registra la ocupación de un espacio por parte de un grupo
$registroEspacio = new RegistroEspacio($conexion);

$idRegistroEspacio = $registroEspacio->registrarEspacio(
    $tipoEspacio,
    $nroEspacio,
    $grupo
);


$conectorPDO->desconectar();
header("Location: solicitanteRegistroIncidencias.php?idRegistroEspacio=" . $idRegistroEspacio);
exit;

?>