<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/inicioSesion.css">
    <script src="assets/js/inicioSesion.js"></script>
    
</head>


<body>
    

    <header>
        <img src="assets/img/logoForfols.png" alt="logo de forfols" class="logoForfols">
        <img src="assets/img/logoSGRSI.png" alt="logo SGRSI" class="logoSGRSI">
        <img src="assets/img/logoITI.png" alt="logo de la ITI" class="logoITI">
    </header>

 <button type="button" id="btnObtenerUsuarios">Usuarios predefinidos</button>

    <section class="inicioSesion">
    
    <form action="procesarInicioSesion.php" method="post">
     <h1>Iniciar Sesión</h1>

    <label for="ci">Cédula:</label>
    <input type="text" id="ci" name="ci" required>
    
    <label for="contra">Contraseña:</label>
    <input type="password" id="contra" name="contra" required>
    

    <button type="submit" class="btn btn-outline-danger" id="btnIniciar">Iniciar sesión</button>
    

    </form>
    </section>

</body>

</html>
