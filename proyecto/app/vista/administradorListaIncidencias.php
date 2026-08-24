<?php
/**
 *administradorListaIncidencias.php incluye solo una vez a config.php,
 *si este ya se encuentra incluido no lo incluye por segunda vez.
 */

require_once __DIR__ . "/../../config/config.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= URL_PUBLIC . '/assets/css/administrador.css' ?>">
</head>

<body>
    <header>
        <nav>
            <a href="<?= URL_PUBLIC . '/indexAdministrador.php' ?>">
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
                <th>Nro espacio</th>
                <th>Grupo</th>
                <th>PC</th>
                <th>Persona asignada PC</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha creado</th>
            </tr>
            <tbody id="tabla"></tbody>
            <?php
            /**
             * Recorre cada incidencia y la pone en su fila correspondiente en la tabla.
             */
             foreach ($incidencias as $incidencia) {
                ?>
                    <?php $fechaCambiada = date("d/m/Y H:i", strtotime($incidencia["fecha"])); ?>
                    <tr>
                        <td><?= htmlspecialchars($incidencia["nombreSolicitante"]) ?></td>
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
                    </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

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
<script src="<?= URL_PUBLIC . '/assets/js/administradorListaIncidencias.js' ?>"></script>
</body>

</html>