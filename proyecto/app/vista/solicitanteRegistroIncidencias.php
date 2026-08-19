<?php

require_once __DIR__ . "/../../config/config.php";
if (!isset($_SESSION["ci"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=sinSesion");
    exit;
}

if (empty($_SESSION["solicitante"])) {
    header("Location:" . URL_PUBLIC . "/cerrarSesion.php?motivo=rol");
    exit;
}

$idRegistroEspacio = $_GET["idRegistroEspacio"] ?? null;

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Registrar Incidencias</title>
  <link rel="stylesheet" href="assets/css/solicitante.css">
  <script src="/GitHub/ramaAlexander/proyecto/public/assets/js/solicitanteRegistroIncidencia.js"></script>
</head>

<body>
  <header>
    <nav class="d-flex justify-content-between align-items-center">
        <button id="btnTerminar" class="btnNav">Terminar</button>

      <a href="<?= URL_PUBLIC . '/cerrarSesion.php' ?>">
        <button class="btnNav">Cerrar sesión</button>
      </a>
    </nav>
  </header>

  <form id="registroIncidencia" class="mt-3" action="procesarRegistroIncidencia.php" method="post">
    <fieldset>
  
    <input type="hidden"
           name="idRegistroEspacio"
           value="<?= $idRegistroEspacio ?>">


      <legend>Registrar Incidencia</legend>
      <div class="mb-2">
        <label for="espacio" >Tipo de Incidencia:</label>
            <select name="tipo" id="tipo">
                <option value="" disabled selected hidden>--</option>
                <option>Otros</option>
                <option>PC</option>
            </select>
      </div>

      <div id="campoExtra" class="d-none">
        <label for="nroPc">Número del PC:</label>
        <input name="nroPc" id="nroPc" type="text" placeholder="Ej: PC03" required>
        
        <label for="nombreAlumno">Nombre del alumno:</label>
        <input name="nombreAlumno" id="nombreAlumno" type="text" placeholder="Ej: Juan Perez">
      </div>

      <div class="mb-2">
      <h4>Descripción de la Incidencia</h4>
      <textarea cols="40" rows="10" id="descripcion" name="descripcion" required
        placeholder="Describe la incidencia..."></textarea>
      </div>

      <input type="submit" value="Registrar Incidencia" class="mt-2">

    </fieldset>
  </form>

</body>

</html>