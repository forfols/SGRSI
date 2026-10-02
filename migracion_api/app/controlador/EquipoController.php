<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EquipoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class EquipoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        RespuestaJson::exito((new EquipoDAO($this->conectar()))->listarEquipos());
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