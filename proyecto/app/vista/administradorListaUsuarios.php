<?php
/**
 *administradorListaUsuarios.php incluye solo una vez a config.php,
 *si este ya se encuentra incluido no lo incluye por segunda vez.
 */

require_once __DIR__ . "/../../config/config.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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
            <?php
            /**
             * Recorre la lista de usuarios y la pone en su respectiva fila de la tabla.
             */
             foreach ($usuarios as $usuario) {
                 ?>

                <?php
                /**
                 * @brief recorre la lista de rol con if, si el usuario coincide con el rol, se le asignan el rol,
                 * y si este no coincide con uno se le deja el estado de "sin rol".
                 * 
                 * @param array $usuario Arreglo asociativo con las claves de los respectivos roles.
                 * 
                 */
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
                    <td>
                        <button type="button" class="btnModificar" data-nombre="<?= htmlspecialchars($usuario["nombre"]) ?>"
                            data-ci="<?= htmlspecialchars(trim($usuario["ci"])) ?>"
                            data-solicitante="<?= $usuario["solicitante"] ?>" data-tecnico="<?= $usuario["tecnico"] ?>"
                            data-administrador="<?= $usuario["administrador"] ?>"
                            data-activo="<?= $usuario["activo"] ?>" >
                            Modificar
                        </button>
                    </td>
                </tr>

            <?php } ?>
            </tbody>

        </table>
    </div>

    <form action="procesarModificarUsuario.php" method="post" id="modificarUsuario">
        <fieldset class="formularioModificarUsuario" style="display:none">

        <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">
        <input type="hidden" id="estaActivo" name="estaActivo">
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
                <input type="ci" id="ci" name="ci" pattern="[1-9][0-9]{7}" title="Ingrese la cédula de 8 dígitos sin puntos ni guiones" inputmode="numeric"
                            maxlength="8" required>
            </div>

            <div>
                <label>Roles:</label>
                <br>
                <input type="checkbox" id="rolSolicitante" name="solicitante" value="1">
                <label for="rolSolicitante">Solicitante</label>

                <input type="checkbox" id="rolTecnico" name="tecnico" value="1">
                <label for="rolTecnico">Técnico</label>

                <input type="checkbox" id="rolAdministrador" name="administrador" value="1">
                <label for="rolAdministrador">Administrador</label>
                <br>
            </div>



            <p>
                <input type="submit" value="Actualizar">
            </p>
        </fieldset>
    </form>
    <script src="<?= URL_PUBLIC . '/assets/js/administradorListaUsuarios.js' ?>"></script>
</body>

</html>