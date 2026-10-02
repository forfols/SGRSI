<?php
/**
 * tecnico.php incluye solo una vez a config.php,
 * si este ya se encuentra incluido no lo incluye por segunda vez.
 */

require_once __DIR__ . "/../../config/config.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= URL_PUBLIC . '/assets/css/tecnico.css' ?>">
</head>

<body>
    <header>
        <nav class="d-flex justify-content-between align-items-center">
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

    <h1>Lista de Incidencias</h1>
    <div class="table-responsive">
        <table border="1">
            <tr>
                <th>Docente</th>
                <th>Tipo incidencia</th>
                <th>Número de PC</th>
                <th>Alumno asignado</th>
                <th>Espacio</th>
                <th>Nro espacio</th>
                <th>Grupo</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Fecha creado</th>
            </tr>
            <tbody id="tabla"></tbody>
            <?php
            /**
             * Se muestran todas incidencias que puede ver el Técnico en sesión.
             */
            foreach ($incidencias as $incidencia) { ?>
                <?php
                /**
                 * Muestra las inciencias que están sin asignar y muestra las incidencias
                 * que concuerdan con el ci del técnico.
                 */
                if (($incidencia["tipoEstado"] == "Sin asignar") || ($incidencia["ciTecnico"] == $_SESSION["ci"])) { ?>
                <?php
                /**
                 * Convierte la fecha que se registra en la base de datos
                 * a un formato legible dia/mes/año y el horario de la incidencia
                 * para mostrarse en la tabla.
                 */
                $fechaCambiada = date("d/m/Y H:i", strtotime($incidencia["fecha"])); ?>
                <tr>
                    <td><?= htmlspecialchars($incidencia["nombreSolicitante"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["tipoIncidencia"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["nombreEquipo"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["alumno"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["tipoEspacio"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["numeroEspacio"]) ?></td>
                    <td><?= htmlspecialchars($incidencia["nombreGrupo"]) ?></td>
                    <td><button type="button" class="btnModificarEstado"
                            data-id="<?= htmlspecialchars($incidencia["id"]) ?>"
                            data-estado="<?= htmlspecialchars($incidencia["tipoEstado"]) ?>"
                            data-prioridad="<?= htmlspecialchars($incidencia["prioridad"]) ?>"
                            data-diagnostico="<?= htmlspecialchars($incidencia["diagnostico"]) ?>"
                            data-soluciones="<?= htmlspecialchars($incidencia["soluciones"]) ?>">
                            Modificar
                        </button></td>
                    <td><?= htmlspecialchars($incidencia["descripcionIncidencia"]) ?></td>
                    <td><?= htmlspecialchars($fechaCambiada) ?></td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <form action="procesarModificarEstado.php" method="post" id="modificarEstado">
        <fieldset class="formularioModificarEstado" style="display:none">
            <legend>
                <h2>
                    Modificar Estado
                </h2>
            </legend>

            <button type="button" id="btnCerrarModificarEstado" onclick="formularioModificarEstado()">x</button>

            <input type="hidden" name="idIncidencia" id="idIncidenciaEstado">
            <input type="hidden" name="ciTecnico" value="<?php $_SESSION["ci"] ?>">
            <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">

            <br>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option>Pendiente</option>
                <option>En proceso</option>
                <option>Terminado</option>
            </select>
            <br>
            <label for="prioridad">Nivel de prioridad:</label>
            <select id="prioridad" name="prioridad" required>
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

    <script src="<?= URL_PUBLIC . '/assets/js/tecnico.js' ?>"></script>
</body>

</html>