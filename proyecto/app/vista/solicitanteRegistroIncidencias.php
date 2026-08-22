<?php

require_once __DIR__ . "/../../config/config.php";

$idRegistroEspacio = $_GET["idRegistroEspacio"] ?? null;
//$tipoEspacio = $_GET["tipoEspacio"] ?? null;

require_once RUTA_CONTROLADOR . "/procesarRecibirEquipo.php";

//var_dump($tipoEspacio);
//exit;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <title>Registrar Incidencias</title>
  <link rel="stylesheet" href="<?= URL_PUBLIC . '/assets/css/solicitante.css' ?>">
    <script>
          const equipos = <?= json_encode($equipos) ?>;
          const tipoEspacio = <?= json_encode($_GET["tipoEspacio"]) ?>;
          const alumnoAsignado = <?= json_encode($_SESSION["nombre"]) ?>;
</script>
  <script src="<?= URL_PUBLIC . '/assets/js/solicitanteRegistroIncidencia.js' ?>"></script>
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

      <?php
    if (isset($_SESSION["error"])) {
        echo "<div class='alerta'>" . htmlspecialchars($_SESSION["error"]) . "</div>";
        unset($_SESSION["error"]);
    }
    if (isset($_SESSION["mensaje"])) {
        echo "<div class='mensaje'>" . htmlspecialchars($_SESSION["mensaje"]) . "</div>";
        unset($_SESSION["mensaje"]);
    }
    ?>

  <form id="registroIncidencia" class="mt-3" action="procesarRegistroIncidencia.php" method="post">
    <fieldset>
  
    <input type="hidden" name="idRegistroEspacio" value="<?= $idRegistroEspacio ?>">
      <input type="hidden" name="tipoEspacio" value="<?= $_GET["tipoEspacio"] ?>">


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

        <label for="nroPc">PC:</label>
        <select name="nroPc" id="nroPc">
            </select>
        
        <label for="nombreAlumno">Persona asignada:</label>
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