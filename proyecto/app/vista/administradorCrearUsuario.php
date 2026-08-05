<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/administrador.css">
    <script src="assets/js/administradorCrearUsuario.js"></script>
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

    <p>
    <h1>Crear Usuario</h1>
    </p>
    <form id="crearUsuario">
        <div>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>

        <div>
            <label for="ci">Cédula:</label>
            <input type="ci" id="ci" name="ci" required>
        </div>

        <div>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contra" name="contraseña" required>
        </div>

        <div>
            <label for="repetirContraseña">Repetir contraseña:</label>
            <input type="password" id="repetirContra" name="repetirContraseña" required>
        </div>

        <div>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="solicitante">Solicitante</option>
                <option value="tecnico">Tecnico</option>
            </select>
        </div>

        <button type="submit" class="mt-2" id="btnCrear">Crear Usuario</button>
</body>

</html>