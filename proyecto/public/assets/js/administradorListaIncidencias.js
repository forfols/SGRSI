const cuerpoTabla = document.getElementById("cuerpoTabla");

function agregarFila(incidencia) {
    const fila = document.createElement("tr");

    fila.dataset.estado = incidencia.tipoEstado;
    fila.dataset.tipo = incidencia.tipoIncidencia;
    fila.dataset.general = incidencia.nombreIncidenciaGeneral;

    celda(fila, incidencia.nombreSolicitante);
    celda(fila, incidencia.tipoIncidencia);
    celda(fila, incidencia.tipoEspacio);
    celda(fila, incidencia.numeroEspacio);
    celda(fila, incidencia.nombreGrupo);
    celda(fila, incidencia.nombreEquipo);
    celda(fila, incidencia.alumno);
    celda(fila, incidencia.descripcionIncidencia);

    const campoEstado = document.createElement("td");
    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "Ver estado";
    boton.addEventListener("click", () => abrirVerEstado(incidencia));
    campoEstado.appendChild(boton);
    fila.appendChild(campoEstado);

    celda(fila, formatearFecha(incidencia.fecha));

    cuerpoTabla.appendChild(fila);

}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const incidencias = await api("GET", "incidencias.php?rol=administrador");

    for (const incidencia of incidencias) {
        agregarFila(incidencia);
    }
    aplicarFiltro();
    buscador();
    paginar();
}

function aplicarFiltro(){
    const porCampo ={};
    document.querySelectorAll('.filtro:checked').forEach(c =>{
        (porCampo[c.dataset.campo] ??= []).push(c.value);    
    });

    document.querySelectorAll('#cuerpoTabla tr').forEach(fila => {
    const visible = Object.entries(porCampo).every(([campo, valores]) => 
            valores.includes(fila.dataset[campo])
        );
        fila.classList.toggle('oculto-filtro', !visible);
    });

    const total =  document.querySelectorAll('.filtro:checked').length;
    document.getElementById('btn-filtrar').textContent = total ? `Filtrar (${total})` : 'Filtrar';
    
}
document.addEventListener('change', e => {
    if (e.target.classList.contains('filtro')) {aplicarFiltro(); paginaActual = 1; paginar();}
})


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
        !f.classList.contains('oculto-busqueda') &&
        !f.classList.contains('oculto-filtro')
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