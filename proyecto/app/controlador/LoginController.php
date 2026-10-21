<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";
require_once RUTA_NUCLEO . "/Presencia.php";

class LoginController
{
    public function autenticar(string $metodo): void
    {
        if ($metodo !== "POST") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        $ci = trim($datos["ci"] ?? "");
        $contra = $datos["contra"] ?? "";

        if ($ci === "" || $contra === "") {
            RespuestaJson::error("Cédula y contraseña son obligatorias", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new UsuarioDAO($conexion);
        $usuario = $dao->buscarUsuario($ci);
        unset($dao, $conexion);
        Conexion::desconectar();

        if ($usuario === null || !password_verify($contra, $usuario->getClaveHash())) {
            RespuestaJson::error("Usuario o credenciales incorrectas", 401);
        }

        if (Presencia::estaActivo($usuario->getCi())) {
            RespuestaJson::error("Este usuario ya está conectado en otro dispositivo", 409);
        }

        Presencia::registrar($usuario->getCi());

        RespuestaJson::exito([
            "mensaje" => "Sesión iniciada correctamente",
            "token" => Token::generar($usuario),
            "nombre" => $usuario->getNombre(),
            "roles" => [
                "solicitante" => $usuario->getRolSolicitante(),
                "tecnico" => $usuario->getRolTecnico(),
                "administrador" => $usuario->getRolAdministrador(),
            ],
        ]);
    }

    public function latido(string $metodo): void
    {
        if ($metodo !== "POST") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $claims = Token::leer();
        Presencia::registrar((string) $claims["sub"]);

        RespuestaJson::exito(["ok" => true]);
    }

    public function logout(string $metodo): void
    {
        if ($metodo !== "POST") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $claims = Token::leer();
        Presencia::limpiar((string) $claims["sub"]);

        RespuestaJson::exito(["ok" => true]);

    }
}