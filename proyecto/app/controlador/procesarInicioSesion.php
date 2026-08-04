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
$_SESSION["rol"] = $usuario->getRol();

$rol= $usuario->getRol();

if($rol== "Solicitante") {
    header("Location: indexSolicitante.php");
}else if($rol== "Tecnico") {
    header("Location: tecnico.php");
}else if ($rol== "Administrador"){
    header("Location: indexAdministrador.php");
}


exit;

?>