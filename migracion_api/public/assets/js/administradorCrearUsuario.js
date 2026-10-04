const formularioCrear = document.getElementById("crearUsuario");

async function crearUsuario(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "usuarios.php", {
            ci: document.getElementById("ci").value.trim(),
            nombre: document.getElementById("nombre").value.trim(),
            apellido: document.getElementById("apellido").value.trim(),
            contra: document.getElementById("contra").value,
            repetirContra: document.getElementById("repetirContra").value,
            solicitante: document.getElementById("solicitante").checked,
            tecnico: document.getElementById("tecnico").checked,
            administrador: document.getElementById("administrador").checked
        });

        mostrarMensaje(resultado.mensaje, "mensaje");
        formularioCrear.reset();
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

formularioCrear.addEventListener("submit", crearUsuario);