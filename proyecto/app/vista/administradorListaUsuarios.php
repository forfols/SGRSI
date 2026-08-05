<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/administrador.css">
    <script src="assets/js/administradorListaUsuarios.js"></script>
</head>

<body>
    <header>
        <nav>
            <a href="indexAdministrador.php">
                <button>Volver</button>
            </a>

            <a href="../app/controlador/cerrarSesion.php">
                <button>Cerrar sesión</button>
            </a>
        </nav>
    </header>

    <h3>Lista de Usuarios</h3>
    <div class="table-responsive">
    <table border="1">
        <tr>
            <th>Docente</th>
            <th>CI</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <tbody id="tabla"></tbody>
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