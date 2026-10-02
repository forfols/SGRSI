document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("crearUsuario");

    formulario.addEventListener("submit", function (e) {
        e.preventDefault();

        const nombre = document.getElementById("nombre").value;
        const apellido = document.getElementById("apellido").value;
        const ci = document.getElementById("ci").value;
        const contra = document.getElementById("contra").value;
        const repetirContra = document.getElementById("repetirContra").value;
        const rol = document.getElementById("rol").value;
        const usuario = (nombre + " " + apellido);

        const listaUsuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

        const verificacionCi = listaUsuarios.find(u => u.ci === ci);

        if (verificacionCi) {
            alert("Un usuario con esa cédula ya existe");
        } else if (contra !== repetirContra) {
            alert("Las contraseñas no coinciden");
        } else {
            let contador = parseInt(localStorage.getItem("contadorUsuario")) || 0;
            contador++;
            localStorage.setItem("contadorUsuario", contador);

            const nuevoUsuario = {
                id: contador,
                usuario,
                ci,
                contra,
                rol
            };

            listaUsuarios.push(nuevoUsuario);
            localStorage.setItem("usuarios", JSON.stringify(listaUsuarios));

            alert("Se ha creado el usuario");

            document.getElementById("nombre").value = "";
            document.getElementById("apellido").value = "";
            document.getElementById("ci").value = "";
            document.getElementById("contra").value = "";
            document.getElementById("repetirContra").value = "";
        }







    })

})