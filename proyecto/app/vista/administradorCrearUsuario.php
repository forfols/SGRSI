<?php
/**
 * administradorCrearUsuario.php incluye solo una vez a config.php,
 * si este ya se encuentra incluido no lo incluye por segunda vez.
 */
require_once __DIR__ . "/../../config/config.php";

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/administrador.css">

</head>

<body>
    <header>
        <nav>
            <a href="indexAdministrador.php">
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

    <p>
    <h1>Crear Usuario</h1>
    </p>
    <form id="crearUsuario" action="procesarRegistroUsuario.php" method="post">

        <input type="hidden" name="csrfToken" value="<?=htmlspecialchars($_SESSION["csrfToken"])?>">

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
            <input type="ci" id="ci" name="ci" pattern="[1-9][0-9]{7}" title="Ingrese la cédula de 8 dígitos sin puntos ni guiones" inputmode="numeric"
                            maxlength="8" required>
        </div>

        <div>
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contra" name="contra" required>
        </div>

        <div>
            <label for="repetirContraseña">Repetir contraseña:</label>
            <input type="password" id="repetirContra" name="repetirContra" required>
        </div>

        <div>
            <label >Rol:</label>
            <br>

            <label for="solicitante">Solicitante:</label>
            <input type="checkbox" id="solicitante" name="solicitante" value="activo">
            <label for="tecnico">Tecnico:</label>
            <input type="checkbox" id="tecnico" name="tecnico" value="activo">
            <label for="administrador">Administrador:</label>
            <input type="checkbox" id="administrador" name="administrador" value="activo">

            
        </div>

        <button type="submit" class="mt-2" id="btnCrear">Crear Usuario</button>
</body>

</html>