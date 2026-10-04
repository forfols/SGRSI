<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

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

        if ($usuario === null || !password_verify($contra, $usuario->getClaveHash())) {
            Conexion::desconectar();
            RespuestaJson::error("Usuario o credenciales incorrectas", 401);
        }

        if ($usuario->tieneSesionActiva()) {
            Conexion::desconectar();
            RespuestaJson::error("El usuario ya tiene una sesión activa", 409);
        }

        Sesion::iniciar($usuario);
        Token::generarTokenCSRF();
        $dao->marcarActivo($usuario->getCi(), true);
        Conexion::desconectar();

        RespuestaJson::exito([
            "mensaje" => "Sesión iniciada correctamente",
            "csrfToken" => $_SESSION["csrfToken"],
            "roles" => [
                "solicitante" => $usuario->getRolSolicitante(),
                "tecnico" => $usuario->getRolTecnico(),
                "administrador" => $usuario->getRolAdministrador(),
                "csrfToken" => $_SESSION["csrfToken"],
                "nombre" => $usuario->getNombre(),
            ],
        ]);
    }

    public function cerrarSesion(string $metodo): void
    {
        if ($metodo !== "POST") {
            RespuestaJson::error("Método no permitido", 405);
        }

        Token::verificarCSRF();

        //Se libera el flag "activo" antes de destruir la sesión, porque después ya no existe $_SESSION["ci"]
        $ci = (string) ($_SESSION["ci"] ?? "");
        if ($ci !== "") {
            $conexion = Conexion::conectar();
            (new UsuarioDAO($conexion))->marcarActivo($ci, false);
            Conexion::desconectar();
        }

        Sesion::cerrar();

        RespuestaJson::exito(["mensaje" => "Sesión cerrada correctamente"]);
    }
}