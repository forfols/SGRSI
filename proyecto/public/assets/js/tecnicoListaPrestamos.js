const cuerpoTabla = document.getElementById("cuerpoTabla");

async function devolverPrestamo(prestamo) {
    if (!window.confirm("¿Se devolvió " + prestamo.modelo + "?")) {
        return;
    }

    try {
        const resultado = await api("PUT", "prestamos.php", { idPrestamo: prestamo.id });
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

function agregarFila(prestamo) {
    const fila = document.createElement("tr");

    celda(fila, prestamo.alumno);
    celda(fila, prestamo.ciAlumno);
    celda(fila, prestamo.modelo);
    celda(fila, prestamo.nombreTecnico);
    celda(fila, formatearFecha(prestamo.fechaInicio));
    //Si todavía no se devolvió, la celda queda con un guion
    celda(fila, prestamo.fechaFin === null ? "-" : formatearFecha(prestamo.fechaFin));

    const campoAcciones = document.createElement("td");

    //El botón solo aparece mientras la laptop sigue prestada
    if (prestamo.fechaFin === null) {
        const btnDevolver = document.createElement("button");
        btnDevolver.type = "button";
        btnDevolver.textContent = "Registrar devolución";
        btnDevolver.addEventListener("click", () => devolverPrestamo(prestamo));
        campoAcciones.appendChild(btnDevolver);
    }

    fila.appendChild(campoAcciones);

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const prestamos = await api("GET", "prestamos.php");

    for (const prestamo of prestamos) {
        agregarFila(prestamo);
    }
    buscador();
    paginar();
}

function buscador(){
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
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
        !f.classList.contains('oculto-filtro') &&
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

cargarTabla().catch((error) => mostrarMensaje(error.message));