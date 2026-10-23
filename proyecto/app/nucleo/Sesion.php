<?php
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

class Sesion
{
    private static ?array $claims = null;

    public static function verificarSesion(): void
    {
        self::$claims ??= Token::leer();
    }

    public static function verificarRol(string $rol): void
    {
        self::verificarSesion();

        if (!(self::$claims["roles"][$rol] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
    }

    public static function ci(): string
    {
        return (string) (self::$claims["sub"] ?? "");
    }

    public static function nombre(): string
    {
        return (string) (self::$claims["nombre"] ?? "");
    }
}