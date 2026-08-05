<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../modelo/Usuario.php";
require_once __DIR__ . "/../modelo/AccesoDatosUsuario.php";
require_once __DIR__ . "/../modelo/InicioSesion.php";
require_once __DIR__ . "/../modelo/ConectorPDO.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $mensaje = "Acceso Denegado: Petición incorrecta";
    header("Location: /proyecto/public/inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

//Recupera las credenciales provenientes del formulario
$ci = trim($_POST["ci"] ?? "");
$contra = $_POST["contra"] ?? "";

//Credenciales hardcodeadas, en un futuro van a colocarse en archivos aislados o variables de entorno
$conectorPDO = new ConectorPDO ("localhost:3306", "root", "", "SGRSI");
$conexion = $conectorPDO->establecerConexion();

    $accesoDatosUsuario = new AccesoDatosUsuario($conexion);
    $inicioSesion = new InicioSesion($accesoDatosUsuario);
    $usuario = $inicioSesion->autenticar($ci, $contra);

$conectorPDO->desconectar();

//Si las credenciales no coinciden, muestra el error y detiene el proceso
if ($usuario === null) {
    $mensaje = "Acceso Denegado: La cédula o la contraseña son incorrectas.";
    header("Location: /proyecto/public/inicioSesion.php?error=" . urlencode($mensaje));
    exit;
}

session_start();
    session_regenerate_id(true);

$_SESSION["ci"] = $usuario->getCi();
$_SESSION["solicitante"] = $usuario->getRolSolicitante();
$_SESSION["tecnico"] = $usuario->getRolTecnico();
$_SESSION["administrador"] = $usuario->getRolAdministrador();

$solicitante= $usuario->getRolSolicitante();
$tecnico= $usuario->getRolTecnico();
$administrador= $usuario->getRolAdministrador();

$accesoDatosUsuario->estaActivo($_SESSION["ci"], true);


if(($solicitante == true) && ($tecnico == true) && ($administrador == true)) {
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