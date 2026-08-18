<?php
session_start();
require_once __DIR__ . "/../../config/config.php";
if (!isset($_SESSION["ci"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=sinSesion");
    exit;
}

if (empty($_SESSION["solicitante"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=rol");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro uso del Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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

            <label for="espacio" >Espacio:</label>
            <select id="tipoEspacio" name="tipoEspacio">
                <option>Laboratorio</option>
                <option>Taller</option>
                <option>Teórico</option>
            </select>

            <label for="nroEspacio">Número del espacio:</label>
            <input type="number" id="nroEspacio" name="nroEspacio" placeholder="Ej: 3" required>

            <div class="mb-2">
                <label for="grupo">Grupo:</label>
                <input type="text" id="grupo" name="grupo" placeholder="Ej: 3MA" required>
            </div>

                <button type="submit" class="mt-2">Siguiente</button>

        </fieldset>
    </form>
</body>

</html>