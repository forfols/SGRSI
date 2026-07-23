<?php

require_once __DIR__ . "/../modelo/Usuario.php";
require_once __DIR__ . "/../modelo/ConsultaUsuario.php";
require_once __DIR__ . "/../modelo/InicioSesion.php";

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inicioSesion.php");
    exit;
}

//Recupera las credenciales provenientes del formulario
$ci = trim($_POST["ci"] ?? "");
$contra = $_POST["contra"] ?? "";

$consultaUsuario = new ConsultaUsuario();
$inicioSesion = new InicioSesion($consultaUsuario);

$usuario = $inicioSesion->autenticar($ci, $contra);

//Si las credenciales no coinciden, muestra el error y detiene el proceso
if ($usuario === null) {
    exit("La cédula o la contraseña son incorrectas.");
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