<?php
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SolicitudController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRol("solicitante");

        if ($metodo !== "POST") {
            RespuestaJson::error("Método no permitido", 405);
        }
     

        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        $tipoServicio = trim($datos["tipoServicio"] ?? "");
        $tipoEspacio = trim($datos["tipoEspacio"] ?? "");
        $nroEspacio = trim((string) ($datos["nroEspacio"] ?? ""));
        $grupo = trim($datos["grupo"] ?? "");
        $fecha = trim($datos["fecha"] ?? "");
        $descripcion = trim($datos["descripcion"] ?? "");

        if ($tipoServicio === "" || $tipoEspacio === "" || $nroEspacio === "" || $grupo === "" || $fecha === "" || $descripcion === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        //La cédula y el nombre salen de la sesión, no del formulario
        $ci = htmlspecialchars((string) Sesion::ci());
        $nombre = htmlspecialchars((string) Sesion::nombre());
        $tipoServicio = htmlspecialchars($tipoServicio);
        $tipoEspacio = htmlspecialchars($tipoEspacio);
        $nroEspacio = htmlspecialchars($nroEspacio);
        $grupo = htmlspecialchars($grupo);
        $descripcion = htmlspecialchars($descripcion);
        $fechaLatam = date("d/m/Y", strtotime($fecha));

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $_ENV["MAIL_HOST"];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV["MAIL_USERNAME"];
            $mail->Password = $_ENV["MAIL_PASSWORD"];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($_ENV["MAIL_USERNAME"], "SGRSI");
     
            $mail->addAddress("asistentesiti@gmail.com");

            $mail->isHTML(true);
            $mail->Subject = "Nueva solicitud de servicio";
            $mail->Body = "
                <h2>Nueva solicitud de servicio</h2>
                <p>Docente: {$nombre}, {$ci}</p>
                <p>Tipo de servicio: {$tipoServicio}</p>
                <p>Espacio: {$tipoEspacio} {$nroEspacio}</p>
                <p>Grupo: {$grupo}</p>
                <p>Fecha: {$fechaLatam}</p>
                <h3>Descripción</h3>
                <p>{$descripcion}</p>
            ";
            $mail->AltBody = "Nueva solicitud de servicio\nDocente: {$nombre}, {$ci}\nTipo de servicio: {$tipoServicio}\nEspacio: {$tipoEspacio} {$nroEspacio}\nGrupo: {$grupo}\nFecha: {$fechaLatam}\n\nDescripción:\n{$descripcion}";

            $mail->send();
        } catch (Exception $error) {
            RespuestaJson::error("En desarrollo: El servidor no permite el uso de PHPMailer", 500);
        }

        RespuestaJson::exito(["mensaje" => "Se ha enviado la solicitud correctamente"], 201);
    }
}