<?php
require_once RUTA_MODELO . "/Usuario.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

class Sesion
{
    public static function iniciar(Usuario $usuario): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION["ci"] = $usuario->getCi();
        $_SESSION["nombre"] = $usuario->getNombre();
        $_SESSION["solicitante"] = $usuario->getRolSolicitante();
        $_SESSION["tecnico"] = $usuario->getRolTecnico();
        $_SESSION["administrador"] = $usuario->getRolAdministrador();
    }

    public static function cerrar(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $parametros = session_get_cookie_params();
            setcookie(session_name(), "", time() - 42000, $parametros["path"], $parametros["domain"], $parametros["secure"], $parametros["httponly"]);
        }

        session_destroy();
    }

    public static function verificarSesion(): void
    {
        if (!isset($_SESSION["ci"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
    }

    public static function verificarRol(string $rol): void
    {
        self::verificarSesion();

        if (!($_SESSION[$rol] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
    }
}