<?php

/**
 * Controlador encargado de procesar el registro de la ocupación de un espacio
 * por parte de un grupo. Valida la petición y los campos recibidos, y delega
 * la creación del registro en RegistroEspacio.
 */

require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../vendor/autoload.php";
require_once RUTA_MODELO . "/ConectorPDO.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Comprueba que el formulario haya sido enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    header("Location: cerrarSesion.php?motivo=peticionIncorrecta");
    exit;
}

//Recupera las credenciales provenientes del formulario

$tipoServicio = $_POST["tipoServicio"] ?? "";
$tipoEspacio = $_POST["tipoEspacio"] ?? "";
$nroEspacio = $_POST["nroEspacio"] ?? "";
$grupo = $_POST["grupo"] ?? "";
$fecha = $_POST["fecha"] ?? "";
$descripcion = $_POST["descripcion"] ?? "";
$ci = $_POST["ci"] ?? "";
$nombre = $_POST["nombre"] ?? "";


$csrfToken = $_POST["csrfToken"];

//Valida el token CSRF para evitar peticiones falsificadas
if ($csrfToken != $_SESSION["csrfToken"]) {
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

if (($ci != $_SESSION["ci"]) || ($nombre != $_SESSION["nombre"])){
    http_response_code(403);
    header("Location: cerrarSesion.php?motivo=token");
    exit;
}

//Valida que los campos obligatorios hayan sido completados
if ($tipoEspacio === "" || $tipoServicio === "" || $nroEspacio === "" || $grupo === "" || $fecha === "" || $descripcion === "") {
    http_response_code(400);
    $_SESSION["error"] = "No se pudo registrar la solicitud: hay campos vacíos";
    header("Location: solicitanteRegistroSolicitud.php");
    exit;
}

$fechaLatam = date("d/m/Y", strtotime($fecha));

$mail = new PHPMailer(true);

try {

    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = $_ENV["MAIL_HOST"];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["MAIL_USERNAME"];
    $mail->Password = $_ENV["MAIL_PASSWORD"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remitente
    $mail->setFrom(
        $_ENV["MAIL_USERNAME"],
        "SGRSI"
    );

    // Destinatario
    //$mail->addAddress("asistentesiti@gmail.com");
    //mail de testeo
    $mail->addAddress("abogorodskiy@hotmail.com");

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Nueva solicitud de servicio";

    $mail->Body = "
        <h2>Nueva solicitud de servicio</h2>

        <p>Docente: {$nombre}, {$ci} </p>
        <p>Tipo de servicio: {$tipoServicio}</p>
        <p>Espacio: {$tipoEspacio} {$nroEspacio}</p>
        <p>Grupo: {$grupo}</p>
        <p>Fecha: {$fechaLatam}</p>

        <h3>Descripción</h3>
        <p>{$descripcion}</p>
    ";

    $mail->AltBody = "
        Nueva solicitud de servicio

        Docente: {$nombre}, {$ci}

        Tipo de servicio: {$tipoServicio}
        Espacio: {$tipoEspacio} {$nroEspacio}
        Grupo: {$grupo}
        Fecha: {$fechaLatam}

        Descripción:
        {$descripcion}
    ";

    $mail->send();

    $_SESSION["mensaje"] = "Se ha enviado la solicitud correctamente";
    header("Location: solicitanteRegistroSolicitud.php");

} catch (Exception $e) {

    $_SESSION["error"] = "No se pudo enviar la solicitud de servicio";
    header("Location: solicitanteRegistroSolicitud.php");
}

?>