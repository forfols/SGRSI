<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/EquipoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Sesion.php";
require_once RUTA_NUCLEO . "/Conexion.php";

class EquipoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        RespuestaJson::exito((new EquipoDAO(Conexion::conectar()))->listarEquipos());
    }


}