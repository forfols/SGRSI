document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("tipoIncidencia");
    const campoExtra = document.getElementById("campoExtra");
    const nroPC = document.getElementById("nroPC");
    let idActual = null;

    const btnCerrarModificarUsuario = document.getElementById("btnCerrarModificarUsuario");

    btnCerrarModificarUsuario.addEventListener("click", function () {
        document.querySelector(".formularioModificarUsuario").style.display = "none";
    });

        function recargarTabla(){

        const tabla = document.getElementById('tabla');
        tabla.innerHTML = "";


        let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

        usuarios.forEach(function (datosUsuarios) {
            const fila = document.createElement("tr");


            const casillaUsuario = document.createElement('td');
            casillaUsuario.textContent = datosUsuarios.usuario;

            const casillaCi = document.createElement('td');
            casillaCi.textContent = datosUsuarios.ci;

            const casillaContra = document.createElement('td');
            casillaContra.textContent = datosUsuarios.contra;

            const casillaRol = document.createElement('td');
            casillaRol.textContent = datosUsuarios.rol;

            

            // Acciones

            const casillaAcciones = document.createElement('td');
            const article = document.createElement('article');
            article.className = 'acciones';

            const btnModificar = document.createElement('button');
            btnModificar.textContent = 'Modificar';
            

            btnModificar.addEventListener("click", function () {
                idActual = datosUsuarios.id;
                document.querySelector(".formularioModificarUsuario").style.display = "block";

                document.getElementById("nombre").value = datosUsuarios.usuario;
                document.getElementById("ci").value = datosUsuarios.ci;
                document.getElementById("contra").value = datosUsuarios.contra;
                document.getElementById("rol").value = datosUsuarios.rol;
            });

            document.getElementById("modificarUsuario").addEventListener("submit", function (e) {
                e.preventDefault();

                let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

                const mismoUsuario = usuarios.find(datosUsuarios => datosUsuarios.id === idActual);

                if (mismoUsuario) {
                    mismoUsuario.usuario = document.getElementById("nombre").value;
                    mismoUsuario.ci = document.getElementById("ci").value;
                    mismoUsuario.contra = document.getElementById("contra").value;
                    mismoUsuario.rol = document.getElementById("rol").value;
                }

                localStorage.setItem("usuarios", JSON.stringify(usuarios));
                document.querySelector(".formularioModificarUsuario").style.display = "none";
                recargarTabla();
            });

            // Para que los objetos se vean
            fila.appendChild(casillaUsuario);
            fila.appendChild(casillaCi);
            fila.appendChild(casillaContra);
            fila.appendChild(casillaRol);

            fila.appendChild(casillaAcciones);
            casillaAcciones.appendChild(article);
            article.appendChild(btnModificar);

            tabla.appendChild(fila);

        })

    }

    recargarTabla();

    
})
