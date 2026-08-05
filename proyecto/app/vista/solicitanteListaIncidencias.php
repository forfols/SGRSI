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
    <title>Lista de Incidencias</title>
      <script src="assets/js/solicitanteListaIncidencias.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
      <link rel="stylesheet" href="assets/css/solicitante.css">
</head>

<body>
    <header>
        <nav class="d-flex justify-content-between align-items-center">
            <a href=indexSolicitante.php>
                <button class="btnNav">Volver</button>
            </a>

            <a href="../app/controlador/cerrarSesion.php">
                <button class="btnNav">Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <h1>Lista de Incidencias</h1>
    <div class="table-responsive">
    <table border="1">
        <tr>
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

    <form id="modificarIncidencia">
        <fieldset class="formularioModificarIncidencia" style="display:none">
            <legend>
                <h2>
                    Modificar Incidencia
                </h2>
            </legend>

            <button type="button" id="btnCerrarModificarIncidencia" onclick="formularioModificarIncidencia()">x</button>  

            <br>

            <label for="espacio" >Espacio:</label>
            <select id="tipoEspacio">
                <option>Laboratorio</option>
                <option>Taller</option>
                <option>Teórico</option>
            </select>
            <br>
            <label for="nroEspacio">Número del espacio:</label>
            <input name="nroEspacio" type="nroEspacio" id="nroEspacio" placeholder="Ej: 3" required>

            <div>
                <label for="grupo">Grupo:</label>
                <input type="text" id="grupo" name="grupo" placeholder="Ej: 3MA" required>
            </div>

            <div>
        <label for="espacio" >Tipo de Incidencia:</label>
            <select id="tipoIncidencia">
                <option value="" disabled selected hidden>--</option>
                <option>Otros</option>
                <option>PC</option>
            </select>
      </div>

      <div id="campoExtra" class="d-none">
        <label for="nroPC">Número del PC:</label>
        <input name="nroPC" id="nroPC" type="text" placeholder="Ej: PC03" required>
        <br>
        <label for="nombreAlumno">Nombre del alumno:</label>
        <input name="nombreAlumno" id="nombreAlumno" type="text" placeholder="Ej: Juan Perez">
      </div>

      <div>
      <h4>Descripción de la Incidencia</h4>
      <textarea cols="40" rows="10" id="descripcion" name="descripcion" required
        placeholder="Describe la incidencia..."></textarea>
      </div>


            <p>
                <input type="submit" value="Actualizar">
            </p>
        </fieldset>
        </form>

</body>

</html>