<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/GrupoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

require_once RUTA_NUCLEO . "/Sesion.php";

class GrupoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $dao = new GrupoDAO($this->conectar());
        RespuestaJson::exito($dao->listarGrupos());
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