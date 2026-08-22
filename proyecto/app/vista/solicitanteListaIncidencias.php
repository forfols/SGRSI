<?php

require_once __DIR__ . "/../../config/config.php";

require_once RUTA_CONTROLADOR . "/procesarRecibirEspacio.php";
require_once RUTA_CONTROLADOR . "/procesarRecibirEquipo.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= URL_PUBLIC . '/assets/css/solicitante.css' ?>">
    <script>const equipos = <?= json_encode($equipos) ?>;
        const alumnoAsignado = <?= json_encode($_SESSION["nombre"]) ?>;
    </script>

</head>

<body>
    <header>
        <nav class="d-flex justify-content-between align-items-center">
            <a href="<?= URL_PUBLIC . '/indexSolicitante.php' ?>">
                <button class="btnNav">Volver</button>
            </a>

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

    <h1>Lista de Incidencias</h1>
    <div class="table-responsive">
        <table border="1">
            <tr>
                <th>Tipo incidencia</th>
                <th>Espacio</th>
                <th>Nro espacio</th>
                <th>Grupo</th>
                <th>PC</th>
                <th>Persona asignada PC</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha creado</th>
                <th>Acciones</th>
            </tr>
            <tbody id="tabla"></tbody>
            <?php foreach ($incidencias as $incidencia) { ?>
                <?php if (($incidencia["ciSolicitante"] == $_SESSION["ci"])) { ?>
                    <?php $fechaCambiada = date("d/m/Y H:i", strtotime($incidencia["fecha"])); ?>
                    <tr>
                        <td><?= htmlspecialchars($incidencia["tipoIncidencia"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["tipoEspacio"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["numeroEspacio"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["nombreGrupo"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["nombreEquipo"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["alumno"]) ?></td>
                        <td><?= htmlspecialchars($incidencia["descripcionIncidencia"]) ?></td>

                        <td>
                            <button type="button" class="btnVerEstado"
                                data-estado="<?= htmlspecialchars($incidencia["tipoEstado"]) ?>"
                                data-prioridad="<?= htmlspecialchars($incidencia["prioridad"]) ?>"
                                data-diagnostico="<?= htmlspecialchars($incidencia["diagnostico"]) ?>"
                                data-solucion="<?= htmlspecialchars($incidencia["soluciones"]) ?>"
                                data-tecnico="<?= htmlspecialchars($incidencia["nombreTecnico"]) ?>">
                                Ver estado
                            </button>
                        </td>
                        <td><?= htmlspecialchars($fechaCambiada) ?></td>
                        <td>
                            <button type="button" class="btnModificar" data-id="<?= htmlspecialchars($incidencia["id"]) ?>"
                                data-estado="<?= htmlspecialchars($incidencia["tipoEstado"]) ?>"
                                data-tipo-espacio="<?= htmlspecialchars($incidencia["tipoEspacio"]) ?>">
                                Modificar
                            </button>
                            <button type="button" class="btnEliminar" data-id="<?= htmlspecialchars($incidencia["id"]) ?>"
                                data-estado="<?= htmlspecialchars($incidencia["tipoEstado"]) ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <form action="procesarEliminarIncidencia.php" method="post" id="eliminarIncidencia">
        <input type="hidden" name="idIncidencia" id="idIncidenciaEliminar">
        <input type="hidden" name="estadoIncidencia" id="estadoEliminar">
        <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">

    </form>

    <form action="procesarModificarIncidencia.php" method="post" id="modificarIncidencia">
        <fieldset class="formularioModificarIncidencia" style="display:none">
            <legend>
                <h2>
                    Modificar Incidencia
                </h2>
            </legend>

            <button type="button" id="btnCerrarModificarIncidencia">x</button>

            <br>
            <input type="hidden" name="idIncidencia" id="idIncidenciaModificar">
            <input type="hidden" name="estadoIncidencia" id="estadoModificar">
            <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">

            <div>
                <label for="tipoIncidencia">Tipo de Incidencia:</label>
                <select name="tipoIncidencia" id="tipoIncidencia">
                    <option value="" disabled selected hidden>--</option>
                    <option>Otros</option>
                    <option>PC</option>
                </select>
            </div>

            <div id="campoExtra" class="d-none">
                <label for="nroPc">PC:</label>
                <select name="nroPc" id="nroPc">
                </select>
                <label for="nombreAlumno">Nombre de la Persona:</label>
                <input name="nombreAlumno" id="nombreAlumno" type="text" placeholder="Ej: Juan Perez">
            </div>

            <div class="mb-2">
                <h4>Descripción de la Incidencia</h4>
                <textarea cols="40" rows="10" id="descripcion" name="descripcion" required
                    placeholder="Describe la incidencia..."></textarea>
            </div>


            <p>
                <input type="submit" value="Actualizar">
            </p>
        </fieldset>
    </form>

    <form id="verEstado">
        <fieldset class="formularioVerEstado" style="display:none">

            <legend>
                <h2>Estado de la Incidencia</h2>
            </legend>

            <button type="button" id="btnCerrarVerEstado">x</button>

            <div>
                <label for="estado">Estado:</label>
                <span id="estado"></span>
            </div>

            <div id="campoTecnico">
                <label for="tecnico">Técnico asignado:</label>
                <span id="tecnico"></span>
            </div>

            <div>
                <label for="prioridad">Prioridad:</label>
                <span id="prioridad"></span>
            </div>

            <div>
                <label for="diagnostico">Diagnóstico:</label>
                <span id="diagnostico"></span>
            </div>

            <div id="campoSolucion">
                <label for="solucion">Solución:</label>
                <span id="solucion"></span>
            </div>

        </fieldset>

    </form>

    <script src="<?= URL_PUBLIC . '/assets/js/solicitanteListaIncidencias.js' ?>"></script>
</body>

</html>