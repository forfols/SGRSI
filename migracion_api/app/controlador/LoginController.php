<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
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

        $usuario = (new UsuarioDAO($this->conectar()))->buscarUsuario($ci);

        $dao = new UsuarioDAO($this->conectar());
        $resultado = $dao->buscarUsuario($ci);

        if ($resultado === null) {
        RespuestaJson::error("Usuario o credenciales incorrectas", 401);
        }

        [$usuario, $sesionActiva] = $resultado;

        // Si tu clave está en texto plano: $contra !== $usuario->getClaveHash()
        if (!password_verify($contra, $usuario->getClaveHash())) {
        RespuestaJson::error("Usuario o credenciales incorrectas", 401);
        }

if ($sesionActiva) {
    RespuestaJson::error("El usuario ya tiene una sesión activa", 409);
}

Sesion::iniciar($usuario);
Token::generarTokenCSRF();
$dao->marcarActivo($usuario->getCi(), true);    

        RespuestaJson::exito([
            "mensaje" => "Sesión iniciada correctamente",
            "csrfToken" => $_SESSION["csrfToken"],
            "roles" => [
                "solicitante" => $usuario->getRolSolicitante(),
                "tecnico" => $usuario->getRolTecnico(),
                "administrador" => $usuario->getRolAdministrador(),
            ],
        ]);
    }

    public function cerrarSesion(string $metodo): void
    {
    if ($metodo !== "POST") {
        RespuestaJson::error("Método no permitido", 405);
    }

    Token::verificarCSRF();

    // Libera el flag "activo" ANTES de destruir la sesión,
    // porque después de Sesion::cerrar() ya no existe $_SESSION["ci"]
    $ci = (string) ($_SESSION["ci"] ?? "");
    if ($ci !== "") {
        (new UsuarioDAO($this->conectar()))->marcarActivo($ci, false);
    }

    Sesion::cerrar();

    RespuestaJson::exito(["mensaje" => "Sesión cerrada correctamente"]);
}   

    private function conectar(): PDO
    {
        try {
            $conector = ConectorPDO::obtenerInstancia($_ENV['DB_HOST'], (int) $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
            return $conector->establecerConexion();
        } catch (PDOException $error) {
            RespuestaJson::error("Error de conexión con la base de datos", 500);
        }
    }
}