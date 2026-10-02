<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EspacioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Token.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class RegistroEspacioController
{
        public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

    match ($metodo) {
        "GET" => RespuestaJson::exito((new EspacioDAO($this->conectar()))->listarEspacios()),
        "POST" => $this->registrar(),
        default => RespuestaJson::error("Método no permitido", 405),
    };
}

    private function registrar(): void
{
    Sesion::verificarRol("solicitante");
    Token::verificarCSRF();
    $this->alta();
}

    private function alta(): void
    {
        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            RespuestaJson::error("JSON inválido", 400);
        }

        $tipo = trim($datos["tipoEspacio"] ?? "");
        $numero = (int) ($datos["nroEspacio"] ?? 0);
        $grupo = trim($datos["grupo"] ?? "");

        if ($tipo === "" || $numero === 0 || $grupo === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }

        $dao = new EspacioDAO($this->conectar());
        $idRegistroEspacio = $dao->registrarUso($tipo, $numero, $grupo);

        if ($idRegistroEspacio === null) {
            RespuestaJson::error("No se pudo registrar el espacio", 400);
        }

        RespuestaJson::exito(["idRegistroEspacio" => $idRegistroEspacio], 201);
    }

    private function conectar(): PDO
    {
        try {
            $conectorPDO = ConectorPDO::obtenerInstancia($_ENV['DB_HOST'], (int) $_ENV['DB_PUERTO'], $_ENV['DB_USUARIO'], $_ENV['DB_CLAVE'], $_ENV['DB_NOMBRE']);
            return $conectorPDO->establecerConexion();
        } catch (PDOException $error) {
            RespuestaJson::error("Error de conexión con la base de datos", 500);
        }
    }
}