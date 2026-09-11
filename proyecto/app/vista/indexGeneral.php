<?php
/**
 *indexGeneral.php incluye solo una vez a config.php,
 *si este ya se encuentra incluido no lo incluye por segunda vez.
 *
 * @brief Se hace un recorrido que verifica la cantidad de roles que tiene el usuario en la sesión.
 * 
 * Recorre todos los roles comprobando si están activos, si ninguno está activo el rol del usuario será "sin rol".
 * 
 * @return int Cantidad de roles activos (0 si no hay ningun rol activo).
 * 
 * Si el usuario tiene solo 1 rol se le regresa al inicio de sesión porque este
 * espacio es para poder seleccionar el rol que el usuario quiera utilizar en el caso
 * que este cuente con más de uno.
 */

require_once __DIR__ . "/../../config/config.php";
$cantidadRoles = 0;
$sinRol=false;

if ($_SESSION["solicitante"]) {
    $cantidadRoles++;
}

if ($_SESSION["tecnico"]) {
    $cantidadRoles++;
}

if ($_SESSION["administrador"]) {
    $cantidadRoles++;
}

if (($_SESSION["solicitante"])== false && ($_SESSION["tecnico"])== false && ($_SESSION["administrador"])== false) {
    $cantidadRoles = 0;
    $sinRol = true;
}


if ($cantidadRoles == 1) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=rol");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccion de rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/indexSolicitante.css">
    <script src="assets/js/solicitanteRegistroIncidencia.js"></script>
</head>

<body>
    <header>
        <nav>
            <a href="<?= URL_PUBLIC . "/cerrarSesion.php" ?>">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <main class="d-flex flex-wrap gap-2 mt-3 justify-content-center">


        <?php
        /**
         * Muestra un mensaje para el usuario cuando no tiene rol.
         */
            if ($sinRol==true) {
        ?>
            <p>
                Este usuario todavía no cuenta con un rol asignado
            </p>
        <?php
        }
        ?>
        
        <?php
        /**
         * Si el usuario cuenta con el rol de solicitante se le muestra el botón para ingresar como solicitante
         */
            if ($_SESSION["solicitante"]) {
        ?>
            <a href="indexSolicitante.php">
                <button>Ingresar como Solicitante</button>
            </a>
        <?php
        }
        ?>

        <?php
        /**
         * Si el usuario cuenta con el rol de técnico se le muestra el botón para ingresar como técnico.
         */
            if ($_SESSION["tecnico"]) {
        ?>
            <a href="<?= URL_CONTROLADOR . '/cargarIncidenciasTecnico.php' ?>">
                <button>Ingresar como Tecnico</button>
            </a>
        <?php
        }
        ?>

        <?php
        /**
         * Si el usuario cuenta con el rol administrador se le muestra el botón para ingresar como administrador.
         */
            if ($_SESSION["administrador"]) {
        ?>
            <a href="indexAdministrador.php">
                <button>Ingresar como Administrador</button>
            </a>
        <?php
        }
        ?>
    </main>


</body>

</html>