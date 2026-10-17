<?php
/** @var string $ruta */
/** @var string $metodo */

require_once RUTA_CONTROLADOR . "/LoginController.php";
// ...
require_once RUTA_CONTROLADOR . "/LoginController.php";
require_once RUTA_CONTROLADOR . "/UsuarioController.php";
require_once RUTA_CONTROLADOR . "/IncidenciaController.php";
require_once RUTA_CONTROLADOR . "/LaptopController.php";
require_once RUTA_CONTROLADOR . "/PrestamoController.php";
require_once RUTA_CONTROLADOR . "/MetricaController.php";
require_once RUTA_CONTROLADOR . "/SolicitudController.php";
require_once RUTA_CONTROLADOR . "/EquipoController.php";
require_once RUTA_CONTROLADOR . "/GrupoController.php";
require_once RUTA_CONTROLADOR . "/RegistroEspacioController.php";

// Espacio donde se encuentran los endpoints que establecen la comunicación con el controlador
switch ($ruta) {
    case "login":
        (new LoginController())->autenticar($metodo);
        break;
    case "usuarios":
        (new UsuarioController())->gestionar($metodo);
        break;
    case "incidencias":
        (new IncidenciaController())->gestionar($metodo);
        break;
    case "laptops":
        (new LaptopController())->gestionar($metodo);
        break;
    case "prestamos":
        (new PrestamoController())->gestionar($metodo);
        break;
    case "metricas":
        (new MetricaController())->gestionar($metodo);
        break;
    case "solicitudes":
        (new SolicitudController())->gestionar($metodo);
        break;
    case "equipos":
        (new EquipoController())->gestionar($metodo);
        break;
    case "grupos":
        (new GrupoController())->gestionar($metodo);
        break;
    case "espacios":
        (new RegistroEspacioController())->gestionar($metodo);
        break;
    default:
        RespuestaJson::error("Ruta no encontrada", 404);
}