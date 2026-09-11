<?php
/**
 * solicitanteRegistroSolicitud.php incluye solo una vez a config.php,
 * si este ya se encuentra incluido no lo incluye por segunda vez.
 */
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/procesarRecibirEspacio.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitar Servicio</title>
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

  <?php
    /**
     * @brief Muestra un mensaje de error almacenado en sesión, si es que este existe
     * 
     * Verifica si la clave "error" se encuentra dentro de la sesión, si esta existe la imprime dentro de un div con clase "alerta".
     * Se utiliza htmlspecialchars para prevenir inyecciones de xss. Despues de esto se elimina de la variable de sesión para que
     * el mensaje no se repita en una recarga de la página.
     * @return string $_SESSION["error"] Mensaje de error que se muestra, si existe.
     */
    if (isset($_SESSION["error"])) {
        echo "<div class='alerta'>" . htmlspecialchars($_SESSION["error"]) . "</div>";
        unset($_SESSION["error"]);
    }
    if (isset($_SESSION["mensaje"])) {
        echo "<div class='mensaje'>" . htmlspecialchars($_SESSION["mensaje"]) . "</div>";
        unset($_SESSION["mensaje"]);
    }
    ?>

  <form autocomplete="on" action="procesarMail.php" method="POST" class="mt-3">
    <fieldset>

    <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">
    <input type="hidden" name="ci" value="<?=htmlspecialchars($_SESSION["ci"])?>">
    <input type="hidden" name="nombre" value="<?=htmlspecialchars($_SESSION["nombre"])?>">

      <legend>Registrar Solicitud de servicio</legend>

      <div class="mb-2">
        <label for="tipoServicio">Seleccionar tipo de servicio:</label>
        <select name="tipoServicio" required>
          <option>Preparación de laboratorio</option>
          <option>Instalación de software</option>
          <option>Configuración de equipos</option>
        </select>
      </div>

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

      <div class="mb-2">
        <label for="fecha">Selecciona una fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
      </div>


      <div class="mb-2">
        <h4>Descripción del Servicio</h4>
        <textarea cols="40" rows="10" id="descripcion" name="descripcion" placeholder="Describe el servicio..."
          required></textarea>
      </div>
      <button type="submit" class="mt-2">Enviar</button>

      <p>Por favor, evitar darle más de una vez al botón de enviar para no mandar solicitudes repetidas</p>

    </fieldset>
  </form>

  

  <script>
    const espacios = <?= json_encode($espacios) ?>;
  </script>
  <script src="<?= URL_PUBLIC . '/assets/js/solicitanteRegistroSolicitud.js' ?>"></script>

</body>

</html>