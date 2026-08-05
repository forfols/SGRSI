<?php
session_start();

if (!isset($_SESSION["ci"])) {
    header("Location: /GitHub/ramaAlexander/proyecto/public/cerrarSesion.php?motivo=sinSesion");
    exit;
}

if (empty($_SESSION["solicitante"])) {
    header("Location: /GitHub/ramaAlexander/proyecto/public/cerrarSesion.php?motivo=rol");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/indexSolicitante.css">
</head>

<body>
    <header>
        <nav>
            <a href="../app/controlador/cerrarSesion.php">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <main class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
        <a href="solicitanteRegistroEspacio.php">
            <button class="btnPrincipal">Registrar Incidencia</button>
        </a>

        <a href="solicitanteRegistroSolicitud.php">
            <button class="btnPrincipal">Registrar Solicitud</button>
        </a>

        <a href="solicitanteListaIncidencias.php">
            <button class="btnPrincipal">Ver Incidencias Realizadas</button>
        </a>
    </main>


</body>

</html>