const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioEstado = document.getElementById("modificarEstado");
const campoEstado = document.querySelector(".formularioModificarEstado");
const btnCerrar = document.getElementById("btnCerrarModificarEstado");
const estado = document.getElementById("estado");
const prioridad = document.getElementById("prioridad");
const diagnostico = document.getElementById("diagnostico");
const soluciones = document.getElementById("soluciones");
const campoExtra = document.getElementById("campoExtra");

let idEnEdicion = 0;

function actualizarCampoSoluciones() {
    const terminado = estado.value === "Terminado";

    campoExtra.classList.toggle("d-none", !terminado);
    soluciones.required = terminado;
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
    if (e.target.classList.contains('filtro')) {
        aplicarFiltro(); paginaActual = 1; paginar();
    }
});


function abrirModificarEstado(incidencia) {
    idEnEdicion = incidencia.id;

    //Una incidencia "Sin asignar" todavía no tiene valores propios del técnico
    estado.value = ["Pendiente", "En proceso", "Terminado"].includes(incidencia.tipoEstado) ? incidencia.tipoEstado : "Pendiente";
    prioridad.value = ["Alto", "Medio", "Bajo"].includes(incidencia.prioridad) ? incidencia.prioridad : "Medio";
    diagnostico.value = incidencia.diagnostico === "N/A" ? "" : incidencia.diagnostico;
    soluciones.value = incidencia.soluciones === "N/A" ? "" : incidencia.soluciones;

    actualizarCampoSoluciones();
    campoEstado.style.display = "block";
}

function cerrarModificarEstado() {
    campoEstado.style.display = "none";
}

function agregarFila(incidencia) {
    const fila = document.createElement("tr");

    fila.dataset.estado = incidencia.tipoEstado;
    fila.dataset.tipo = incidencia.tipoIncidencia;
    fila.dataset.general = incidencia.nombreIncidenciaGeneral;

    celda(fila, incidencia.nombreSolicitante);
    celda(fila, incidencia.tipoIncidencia);
    celda(fila, incidencia.nombreIncidenciaGeneral);
    celda(fila, incidencia.nombreEquipo);
    celda(fila, incidencia.alumno);
    celda(fila, incidencia.tipoEspacio);
    celda(fila, incidencia.numeroEspacio);
    celda(fila, incidencia.nombreGrupo);

    // columna Estado: el botón "Modificar"
    const campoBoton = document.createElement("td");
    const boton = document.createElement("button");
    boton.type = "button";
    boton.textContent = "Modificar";
    boton.addEventListener("click", () => abrirModificarEstado(incidencia));
    campoBoton.appendChild(boton);
    fila.appendChild(campoBoton);

    celda(fila, incidencia.descripcionIncidencia);
    celda(fila, formatearFecha(incidencia.fecha));

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const incidencias = await api("GET", "incidencias.php?rol=tecnico");

    for (const incidencia of incidencias) {
        agregarFila(incidencia);
    }
    aplicarFiltro();
    buscador();
    paginar();
}

async function modificarEstado(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("PUT", "incidencias.php", {
            accion: "estado",
            idIncidencia: idEnEdicion,
            estado: estado.value,
            prioridad: prioridad.value,
            diagnostico: diagnostico.value,
            soluciones: soluciones.value
        });

        cerrarModificarEstado();
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
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
    

estado.addEventListener("change", actualizarCampoSoluciones);
btnCerrar.addEventListener("click", cerrarModificarEstado);
formularioEstado.addEventListener("submit", modificarEstado);

cargarTabla().catch((error) => mostrarMensaje(error.message));