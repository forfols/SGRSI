<?php

require_once __DIR__ . "/../../config/config.php";
if (!isset($_SESSION["ci"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=sinSesion");
    exit;
}

if (empty($_SESSION["administrador"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=rol");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/indexAdministrador.css">
</head>

<body>
    <header>
        <nav>
            <a href="<?= URL_PUBLIC . '/cerrarSesion.php' ?>">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <main class="d-flex flex-wrap gap-2 mt-3 justify-content-center">

        <a href="administradorCrearUsuario.php">
            <button class="btnPrincipal">Crear usuario</button>
        </a>

        <a href="<?= URL_CONTROLADOR . '/cargarUsuarios.php' ?>">
            <button class="btnPrincipal">Gestionar usuarios</button>
        </a>

        <a href="administradorListaIncidencias.php">
            <button class="btnPrincipal">Ver incidencias generales</button>
        </a>
        
        <a href="administradorMetricas.php">
            <button class="btnPrincipal">Panel de métricas y reportes</button>
        </a>
    </main>

</body>

</html>