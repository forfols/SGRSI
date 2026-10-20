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

    fila.dataset.datos = usuario.nombre;
    fila.dataset.datos = usuario.ci

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
    buscador();
    paginar();
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

function buscador(){
    const texto = (document.getElementById('buscador')?.value ?? '').toLowerCase().trim();
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const celdas = Array.from(fila.cells);
        const contenido = celdas.filter(c => !c.querySelector('button')).map(c => c.textContent).join(' ').toLowerCase();
        fila.classList.toggle('oculto-busqueda',texto !== '' && !contenido.includes(texto));
    });
}

document.getElementById('buscador').addEventListener('input', () => {
    buscador(); paginaActual = 1; paginar();
});

const FILAS_POR_PAGINA = 10;
let paginaActual = 1;

function paginar() {
    const filas = [...document.querySelectorAll('#cuerpoTabla tr')];

    const candidatas = filas.filter(f =>
        !f.classList.contains('oculto-busqueda')
    );

    const totalPaginas = Math.max(1, Math.ceil(candidatas.length / FILAS_POR_PAGINA));
    paginaActual = Math.min(paginaActual, totalPaginas);
    const inicio = (paginaActual - 1) * FILAS_POR_PAGINA;
    const visibles = new Set(candidatas.slice(inicio, inicio + FILAS_POR_PAGINA));

    filas.forEach(f => f.classList.toggle('oculto-pagina', !visibles.has(f)));

    dibujarPaginador(totalPaginas)
}

function dibujarPaginador(totalPaginas){
    const paginador = document.getElementById('paginador')
    if (!paginador) return;

    let html = `<li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
        <button class="page-link" data-pagina="${paginaActual - 1}">Anterior</button></li>`;

    for (let i = 1; i <= totalPaginas; i++) {
        html +=    `<li class="page-item ${i === paginaActual ? 'active' : ''}">
            <button class="page-link" data-pagina="${i}">${i}</button></li>`;
    }

    html += `<li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
        <button class="page-link" data-pagina="${paginaActual + 1}">Siguiente</button></li>`;

    paginador.innerHTML = html;
}

document.addEventListener('click', e => {
    const boton = e.target.closest('[data-pagina]');
    if(boton) {
        paginaActual = Number(boton.dataset.pagina)
        paginar();
    }
});

document.getElementById('buscador').addEventListener('input', buscador);

btnCerrar.addEventListener("click", cerrarModificar);
formularioModificar.addEventListener("submit", modificarUsuario);

cargarTabla().catch((error) => mostrarMensaje(error.message));