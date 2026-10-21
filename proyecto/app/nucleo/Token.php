<?php
require_once RUTA_MODELO . "/Usuario.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class Token
{
    private const DURACION = 3600;

    public static function generar(Usuario $usuario): string
    {
        $ahora = time();

        $claims = [
            "sub" => $usuario->getCi(),
            "nombre" => $usuario->getNombre(),
            "roles" => [
                "solicitante" => $usuario->getRolSolicitante(),
                "tecnico" => $usuario->getRolTecnico(),
                "administrador" => $usuario->getRolAdministrador(),
            ],
            "iat" => $ahora,
            "exp" => $ahora + self::DURACION,
        ];

        return JWT::encode($claims, $_ENV["JWT_SECRETO"], "HS256");
    }

    //Lee y verifica el token del header Authorization. Si no es válido responde 401
    public static function leer(): array
    {
        $cabecera = $_SERVER["HTTP_AUTHORIZATION"] ?? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"] ?? "";

        if (!preg_match('/^Bearer\s+(\S+)$/', $cabecera, $coincidencia)) {
            RespuestaJson::error("Acceso denegado: falta el token", 401);
        }

        try {
            $claims = JWT::decode($coincidencia[1], new Key($_ENV["JWT_SECRETO"], "HS256"));
        } catch (ExpiredException $error) {
            RespuestaJson::error("La sesión venció", 401);
        } catch (Throwable $error) {
            RespuestaJson::error("Token inválido", 401);
        }

        return json_decode(json_encode($claims), true);
    }

    public static function ci(): string
{
    return (string) (Token::leer()["sub"] ?? "");
}

}