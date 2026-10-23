<?php
require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/MetricaDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";
require_once RUTA_NUCLEO . "/Conexion.php";
require_once RUTA_NUCLEO . "/Sesion.php";

class MetricaController
{
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRol("administrador");

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $conexion = Conexion::conectar();
        $dao = new MetricaDAO($conexion);

        $metricas = [
            "porEstado" => $dao->incidenciasPorEstado(),
            "porTipo" => $dao->incidenciasPorTipo(),
            "porEspacio" => $dao->incidenciasPorEspacio(),
            "porTecnico" => $dao->incidenciasPorTecnico(),
            "porMes" => $dao->incidenciasPorMes(),
            "porSalon" => $dao->salonesConMasIncidencias(),
        ];

        Conexion::desconectar();

        RespuestaJson::exito($metricas);
    }
}