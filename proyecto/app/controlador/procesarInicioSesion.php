<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_MODELO . "/Usuario.php";
require_once RUTA_MODELO . "/AccesoDatosUsuario.php";
require_once RUTA_MODELO . "/InicioSesion.php";
require_once RUTA_MODELO . "/ConectorPDO.php";


//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
   //$mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario
$ci = trim($_POST["ci"] ?? "");
$contra = $_POST["contra"] ?? "";

//Credenciales hardcodeadas, en un futuro van a colocarse en archivos aislados o variables de entorno
$conectorPDO = new ConectorPDO ($_ENV['DB_HOST'] . ":" . $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: cerrarSesion.php?motivo=sinConexion");
    exit;
}

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $inicioSesion = new InicioSesion($accesoDatosUsuario);
    $usuario = $inicioSesion->autenticar($ci, $contra);

$conectorPDO->desconectar();

//Si las credenciales no coinciden, muestra el error y detiene el proceso
if ($usuario === null) {
    header("Location: cerrarSesion.php?motivo=credenciales");
    exit;
}

if ($usuario->estaActivo()) {
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
    header("Location: tecnico.php");
}else if ($administrador == true){
    header("Location: indexAdministrador.php");
}


exit;

?>