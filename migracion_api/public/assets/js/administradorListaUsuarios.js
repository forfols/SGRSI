const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioModificar = document.getElementById("modificarUsuario");
const campoModificar = document.querySelector(".formularioModificarUsuario");
const btnCerrar = document.getElementById("btnCerrarModificarUsuario");
const entradaNombre = document.getElementById("nombre");
const entradaCi = document.getElementById("ci");
const rolSolicitante = document.getElementById("rolSolicitante");
const rolTecnico = document.getElementById("rolTecnico");
const rolAdministrador = document.getElementById("rolAdministrador");

function textoRoles(usuario) {
    const roles = [];

    if (usuario.solicitante == 1) roles.push("Solicitante");
    if (usuario.tecnico == 1) roles.push("Técnico");
    if (usuario.administrador == 1) roles.push("Administrador");

    return roles.length > 0 ? roles.join(", ") : "Sin rol";
}

function abrirModificar(usuario) {
    entradaNombre.value = usuario.nombre;
    entradaCi.value = usuario.ci;
    rolSolicitante.checked = usuario.solicitante == 1;
    rolTecnico.checked = usuario.tecnico == 1;
    rolAdministrador.checked = usuario.administrador == 1;

    campoModificar.style.display = "block";
}

function cerrarModificar() {
    campoModificar.style.display = "none";
}

function agregarFila(usuario) {
    const fila = document.createElement("tr");

    celda(fila, usuario.nombre);
    celda(fila, usuario.ci);
    celda(fila, textoRoles(usuario));

    const campoBoton = document.createElement("td");
    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "Modificar";
    boton.addEventListener("click", () => abrirModificar(usuario));
    campoBoton.appendChild(boton);
    fila.appendChild(campoBoton);

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const usuarios = await api("GET", "usuarios.php");

    for (const usuario of usuarios) {
        agregarFila(usuario);
    }
}

async function modificarUsuario(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("PUT", "usuarios.php", {
            ci: entradaCi.value,
            nombre: entradaNombre.value.trim(),
            solicitante: rolSolicitante.checked,
            tecnico: rolTecnico.checked,
            administrador: rolAdministrador.checked
        });

        cerrarModificar();
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

btnCerrar.addEventListener("click", cerrarModificar);
formularioModificar.addEventListener("submit", modificarUsuario);

cargarTabla().catch((error) => mostrarMensaje(error.message));