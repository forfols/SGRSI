<?php

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
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

    <h3>Lista de Usuarios</h3>
    <div class="table-responsive">
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>CI</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <tbody id="tabla"></tbody>
        <?php foreach ($usuarios as $usuario) { ?>

                        <?php
                            $roles = "";

                            if ($usuario["solicitante"] == 1) {
                                $roles = "Solicitante";
                            }

                            if ($usuario["tecnico"] == 1) {
                                if ($roles != "") {
                                    $roles = $roles . ", ";
                                }

                                $roles = $roles . "Técnico";
                            }

                            if ($usuario["administrador"] == 1) {
                                if ($roles != "") {
                                    $roles = $roles . ", ";
                                }

                                $roles = $roles . "Administrador";
                            }

                            if ($roles == "") {
                                $roles = "Sin rol";
                            }

                        ?>

                        <tr>
                            <td><?= htmlspecialchars($usuario["nombre"]) ?></td>
                            <td><?= htmlspecialchars($usuario["ci"]) ?></td>
                            <td><?= htmlspecialchars($roles) ?></td>
                            </tr>

                    <?php } ?>
                </tbody>

    </table>
    </div>

    <form id="modificarUsuario">
        <fieldset class="formularioModificarUsuario" style="display:none">
            <legend>
                <h2>
                    Modificar Usuario
                </h2>
            </legend>

            <button type="button" id="btnCerrarModificarUsuario" onclick="formularioModificarUsuario()">x</button>

            <br>

            <div>
                <label for="nombre">Usuario:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="ci">Cédula:</label>
                <input type="ci" id="ci" name="ci" required>
            </div>

            <div>
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="solicitante">Solicitante</option>
                    <option value="tecnico">Tecnico</option>
                </select>
            </div>



            <p>
                <input type="submit" value="Actualizar">
            </p>
        </fieldset>
    </form>

</body>

</html>