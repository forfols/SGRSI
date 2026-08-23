<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/Usuario.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";
require_once RUTA_MODELO . "/InicioSesion.php";
require_once RUTA_MODELO . "/ConectorPDO.php";


//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$ci = trim($_POST["ci"] ?? "");
$contra = $_POST["contra"] ?? "";

if ($ci === "" || $contra === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo iniciar sesión: campos vacíos";
    header("Location:" . URL_PUBLIC . "/inicioSesion.php");
    exit;
}

//Credenciales hardcodeadas, en un futuro van a colocarse en archivos aislados o variables de entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    http_response_code(500);
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $inicioSesion = new InicioSesion($accesoDatosUsuario);
    $usuario = $inicioSesion->autenticar($ci, $contra);

$conectorPDO->desconectar();

//Si las credenciales no coinciden, muestra el error y detiene el proceso
if ($usuario === null) {
    http_response_code(401);
    header("Location: cerrarSesion.php?motivo=credenciales");
    exit;
}

if ($usuario->estaActivo()) {
    http_response_code(409);
    header("Location:" . URL_CONTROLADOR . "/verificarActivo.php?motivo=sesionActiva");
    exit;
}


    session_regenerate_id(true);

$_SESSION["ci"] = $usuario->getCi();
$_SESSION["solicitante"] = $usuario->getRolSolicitante();
$_SESSION["tecnico"] = $usuario->getRolTecnico();
$_SESSION["administrador"] = $usuario->getRolAdministrador();
$_SESSION["nombre"]= $usuario->getNombre();


$solicitante= $usuario->getRolSolicitante();
$tecnico= $usuario->getRolTecnico();
$administrador= $usuario->getRolAdministrador();

$accesoDatosUsuario->estaActivo($_SESSION["ci"], true);

if (!isset($_SESSION["csrfToken"])) {
    $_SESSION["csrfToken"] = bin2hex( random_bytes(32) ); 
}


if((($solicitante == true) && ($tecnico == true) && ($administrador == true)) || (($solicitante == false) && ($tecnico == false) && ($administrador == false))) {
    header("Location: indexGeneral.php");
}else if(($solicitante == true) && (($tecnico == true) || ($administrador == true))) {
    header("Location: indexGeneral.php");
}else if (($tecnico == true) && (($solicitante == true) || ($administrador == true))){
    header("Location: indexGeneral.php");
}else if (($administrador == true) && (($solicitante == true) || ($tecnico == true))){
    header("Location: indexGeneral.php");
}else if ($solicitante == true){
    header("Location: indexSolicitante.php");
}else if ($tecnico == true){
    header("<?= URL_CONTROLADOR . '/cargarIncidenciasTecnico.php' ?>");
}else if ($administrador == true){
    header("Location: indexAdministrador.php");
}


exit;

?>