<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/inicioSesion.css">
    
</head>

<body>

    <header>
        <img src="assets/img/logoForfols.png" alt="logo de forfols" class="logoForfols">
        <img src="assets/img/logoSGRSI.png" alt="logo SGRSI" class="logoSGRSI">
        <img src="assets/img/logoITI.png" alt="logo de la ITI" class="logoITI">
    </header>

    <section class="inicioSesion">

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
?>
    
    <form action="procesarInicioSesion.php" method="post">
     <h1>Iniciar Sesión</h1>

    <label for="ci">Cédula:</label>
    <input type="text" id="ci" name="ci" pattern="[1-9][0-9]{7}" title="Ingrese la cédula de 8 dígitos sin puntos ni guiones" inputmode="numeric"
                            maxlength="8" required>
    
    <label for="contra">Contraseña:</label>
    <input type="password" id="contra" name="contra" required>
    

    <button type="submit" class="btn btn-outline-danger" id="btnIniciar">Iniciar sesión</button>
    

    </form>
    </section>

</body>

</html>
