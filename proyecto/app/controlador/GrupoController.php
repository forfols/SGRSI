<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/GrupoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class GrupoController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarSesion();

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $dao = new GrupoDAO(Conexion::conectar());
        RespuestaJson::exito($dao->listarGrupos());
    }


}