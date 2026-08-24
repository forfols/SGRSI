<?php

/**
 * @file procesarRegistroSolicitud.php
 * @brief Controlador encargado de procesar el registro de una solicitud de servicio.
 *
 * Valida la petición y los campos recibidos, y envía por correo electrónico
 * los datos de la solicitud mediante PHPMailer, utilizando una conexión SMTP.
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

//Valida que la cédula y el nombre recibidos coincidan con los del usuario en sesión
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

//Convierte la fecha recibida al formato d/m/Y para mostrarla en el correo
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

    //Cuerpo del correo en formato HTML, con los datos de la solicitud
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

    //Versión en texto plano del correo, para clientes que no admiten HTML
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

    //Envía el correo con los datos de la solicitud
    $mail->send();

    $_SESSION["mensaje"] = "Se ha enviado la solicitud correctamente";
    header("Location: solicitanteRegistroSolicitud.php");

} catch (Exception $e) {

    //Si ocurre un error al enviar el correo, se informa el error al usuario
    $_SESSION["error"] = "No se pudo enviar la solicitud de servicio";
    header("Location: solicitanteRegistroSolicitud.php");
}

?>