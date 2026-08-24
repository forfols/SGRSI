<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Guarda como constante el directorio padre de config, es decir proyecto
 */
define("RUTA_RAIZ", dirname(__DIR__));

/**
 * Creación de constantes para cada capa
 */
define("RUTA_APP", RUTA_RAIZ . "/app");
define("RUTA_MODELO", RUTA_APP . "/modelo");
define("RUTA_CONTROLADOR", RUTA_APP . "/controlador");
define("RUTA_VISTA", RUTA_APP . "/vista");
define("RUTA_PUBLIC", RUTA_RAIZ . "/public");

/**
 *  Cambiar URL_BASE dependiendo de la url de cada uno
 *  estas url se usaron porque headers y href no permiten el uso de la ruta raiz como parte de la ruta
 */
define("URL_BASE", "/GitHub/SGRSI/proyecto");
define("URL_APP", URL_BASE . "/app");
define("URL_MODELO", URL_APP . "/modelo");
define("URL_CONTROLADOR", URL_APP . "/controlador");
define("URL_VISTA", URL_APP . "/vista");
define("URL_PUBLIC", URL_BASE . "/public");



/**
 * Se cargan las herramientas para generar las variables de entorno
 */
require_once RUTA_RAIZ . "/vendor/autoload.php";

/**
 * Genera una estructura que se almacenará en la memoria estática para guardar las variables de entorno
 */
$dotenv = Dotenv\Dotenv::createImmutable(RUTA_RAIZ);
/**
 * Carga las variables de entorno provenientes de .env, si hay errores retornará excepciones (InvalidPathException, InvalidEncodingException, InvalidFileException)
 */
$dotenv->load();