<?php
//Guarda como constante el directorio padre de config, es decir la raíz del proyecto
define("RUTA_RAIZ", dirname(__DIR__));

//Constantes para cada capa
define("RUTA_APP", RUTA_RAIZ . "/app");
define("RUTA_MODELO", RUTA_APP . "/modelo");
define("RUTA_CONTROLADOR", RUTA_APP . "/controlador");
define("RUTA_VISTA", RUTA_APP . "/vista");
define("RUTA_NUCLEO", RUTA_APP . "/nucleo");
define("RUTA_PUBLIC", RUTA_RAIZ . "/public");


//Se cargan las herramientas para generar las variables de entorno
require_once RUTA_RAIZ . "/vendor/autoload.php";

//Carga las variables de entorno desde .env
$dotenv = Dotenv\Dotenv::createImmutable(RUTA_RAIZ);
$dotenv->load();
