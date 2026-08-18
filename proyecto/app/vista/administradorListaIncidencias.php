<?php
session_start();
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
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/administrador.css">
    <script src="assets/js/administradorListaIncidencias.js"></script>
</head>

<body>
    <header>
        <nav>
            <a href="indexAdministrador.php">
                <button>Volver</button>
            </a>

            <a href="<?= URL_PUBLIC . '/cerrarSesion.php' ?>">
                <button>Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <h1>Lista de Incidencias </h1>
    <div class="table-responsive">
    <table border="1">
        <tr>
            <th>Docente</th>
            <th>Tipo incidencia</th>
            <th>Espacio</th>
            <th>Grupo</th>
            <th>Número de PC</th>
            <th>Alumno asignado</th>
		    <th>Descripción</th>
		    <th>Nro espacio</th>
            <th>Estado</th>
            <th>Fecha creado</th>
        </tr>
        <tbody id="tabla"></tbody>
    </table>
    </div>

    <form id="verEstado">
        <fieldset class="formularioVerEstado" style="display:none">
            <legend>
                <h2>
                    Ver Estado
                </h2>
            </legend>

            <button type="button" id="btnCerrarVerEstado" onclick="formularioVerEstado()">x</button>

            <br>

            <label for="estado">Estado:</label>
            <input type="text" id="estadoVer"></input>
            <br>
            <label for="prioridad">Nivel de prioridad:</label>
            <input type="text" id="prioridadVer"></input>

            <div>
                <h4>Diagnóstico</h4>
                <textarea cols="40" rows="10" id="diagnosticoVer" name="diagnostico"  readonly
                    placeholder="Describe el diagnostico..."></textarea>
            </div>
            <div id="campoExtra" class="d-none">
                <h4>Soluciones (en caso de marcado como resuelto)</h4>
                <textarea cols="40" rows="10" id="solucionesVer" name="soluciones"  readonly
                    placeholder="Describe las soluciones..."></textarea>
            </div>
        </fieldset>
    </form>

</body>

</html>