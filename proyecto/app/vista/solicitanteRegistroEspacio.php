<?php
/**
 * Carga las dependencias necesarias para procesar la recepción de
 * una incidencia relacionada a un espacio
 */
require_once __DIR__ . "/../../config/config.php";

require_once RUTA_CONTROLADOR . "/procesarRecibirEspacio.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro uso del Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/solicitante.css">

</head>

<body>
    <header>
        <nav class="d-flex justify-content-between align-items-center">
            <a href="indexSolicitante.php">
                <button class="btnNav">Volver</button>
            </a>

            <a href="<?= URL_PUBLIC . '/cerrarSesion.php' ?>">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <form id="registroEspacio" class="mt-3" action="procesarRegistroEspacio.php" method="post">
        <fieldset>

        <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">

            <label for="espacio">Espacio:</label>
            <select id="tipoEspacio" name="tipoEspacio" required>
                <option value="">Seleccione un tipo</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Taller">Taller</option>
                <option value="Teórico">Teórico</option>
            </select>

            <label for="nroEspacio">Número del espacio:</label>
            <select id="nroEspacio" name="nroEspacio" required>
            </select>

            <div class="mb-2">
                <label for="grupo">Grupo:</label>
                <select id="grupo" name="grupo" required>
                    <option value="">Seleccione un grupo</option>

                    <?php
                    /**
                     * Recorre todos los grupos que están en el
                     * arreglo de grupos dentro de select.
                     */
                     foreach ($grupos as $grupo): ?>
                        <option value="<?= htmlspecialchars($grupo['nombre']) ?>">
                            <?= htmlspecialchars($grupo['nombre']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <button type="submit" class="mt-2">Siguiente</button>

        </fieldset>
    </form>
</body>

<script>
    const espacios = <?= json_encode($espacios) ?>;
</script>
<script src="<?= URL_PUBLIC . '/assets/js/solicitanteRegistroEspacio.js' ?>"></script>

</html>