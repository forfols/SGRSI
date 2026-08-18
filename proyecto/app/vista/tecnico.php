<?php
session_start();

require_once __DIR__ . "/../../config/config.php";

if (!isset($_SESSION["ci"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=sinSesion");
    exit;
}

if (empty($_SESSION["tecnico"])) {
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
    <link rel="stylesheet" href="assets/css/tecnico.css">
    <script src="assets/js/tecnico.js"></script>
</head>

<body>
    <header>
        <nav class="d-flex justify-content-between align-items-center">
            <a href="<?= URL_PUBLIC . '/cerrarSesion.php' ?>">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <h1>Lista de Incidencias</h1>
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
            <th>Acciones</th>
        </tr>
        <tbody id="tabla"></tbody>
    </table>
    </div>

    <form id="modificarEstado">
        <fieldset class="formularioModificarEstado" style="display:none">
            <legend>
                <h2>
                    Modificar Estado
                </h2>
            </legend>

            <button type="button" id="btnCerrarModificarEstado" onclick="formularioModificarEstado()">x</button>

            <br>

            <label for="estado">Estado:</label>
            <select id="estado" required>
                <option>Pendiente</option>
                <option>En proceso</option>
                <option>Terminado</option>
            </select>
            <br>
            <label for="prioridad">Nivel de prioridad:</label>
            <select id="prioridad" required>
                <option>Alto</option>
                <option>Medio</option>
                <option>Bajo</option>
            </select>

            <div>
                <h4>Diagnóstico</h4>
                <textarea cols="40" rows="10" id="diagnostico" name="diagnostico" required
                    placeholder="Describe el diagnostico..."></textarea>
            </div>
            <div id="campoExtra" class="d-none">
                <h4>Soluciones</h4>
                <textarea cols="40" rows="10" id="soluciones" name="soluciones" required
                    placeholder="Describe las soluciones..."></textarea>
            </div>


            <p>
                <input type="submit" value="Actualizar">
            </p>
        </fieldset>
    </form>


    <form id="verEstado">
        <fieldset class="formularioVerEstado" style="display:none">
            <legend>
                <h2>
                    Ver Estado
                </h2>
            </legend>

            <button type="button" id="btnCerrarVerEstado" onclick="formularioVerEstado()">x</button>

            <br>
            <label for="tecnico">Tecnico asignado:</label>
            <input type="text" id="tecnico" readonly></input>

            <label for="estado">Estado:</label>
            <input type="text" id="estadoVer" readonly></input>
            <br>
            <label for="prioridad">Nivel de prioridad:</label>
            <input type="text" id="prioridadVer" readonly></input>

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