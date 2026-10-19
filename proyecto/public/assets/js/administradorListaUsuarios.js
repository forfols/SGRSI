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

function textoRoles(usuario) {
    return listaRoles(usuario).join(", ");
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

    fila.dataset.rol = listaRoles(usuario).join("|");

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
    aplicarFiltro();
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

function aplicarFiltro(){
    const porCampo ={};
    document.querySelectorAll('.filtro:checked').forEach(c =>{
        (porCampo[c.dataset.campo] ??= []).push(c.value);    
    });

    document.querySelectorAll('#cuerpoTabla tr').forEach(fila => {
    const visible = Object.entries(porCampo).every(([campo, valores]) => {
        const deLaFila = (fila.dataset[campo] ?? "").split("|");
        return valores.some(v => deLaFila.includes(v));
        });
        fila.classList.toggle('d-none', !visible);
    });

    const total =  document.querySelectorAll('.filtro:checked').length;
    document.getElementById('btn-filtrar').textContent = total ? `Filtrar (${total})` : 'Filtrar';
    
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('filtro')) aplicarFiltro();
})

function buscador(){
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const celdas = Array.from(fila.cells).slice(0, -2);
        const contenido = celdas.map(c => c.textContent).join('').toLowerCase();
        fila.style.display = contenido.includes(texto) || texto === '' ? '' : 'none';
    })
}

document.getElementById('buscador').addEventListener('input', buscador);

btnCerrar.addEventListener("click", cerrarModificar);
formularioModificar.addEventListener("submit", modificarUsuario);

cargarTabla().catch((error) => mostrarMensaje(error.message));