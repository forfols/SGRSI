const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioModificar = document.getElementById("modificarIncidencia");
const campoModificar = document.querySelector(".formularioModificarIncidencia");
const btnCerrarModificar = document.getElementById("btnCerrarModificarIncidencia");
const tipoIncidencia = document.getElementById("tipoIncidencia");
const campoExtra = document.getElementById("campoExtra");
const nroPc = document.getElementById("nroPc");
const nombreAlumno = document.getElementById("nombreAlumno");
const incidenciaGeneral = document.getElementById("incidenciaGeneral");
const descripcion = document.getElementById("descripcion");
const avisoDescripcion = document.getElementById("avisoDescripcion");
const nombreUsuario = sessionStorage.getItem("nombre") ?? "";

let equipos = [];
let idEnEdicion = 0;
let espacioEnEdicion = "";

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
    if (e.target.classList.contains('filtro')) aplicarFiltro();
})


function cargarEquipos(equipoSeleccionado) {
    nroPc.replaceChildren();

    for (const equipo of equipos) {
        if (espacioEnEdicion === "Laboratorio" || equipo.nombre === "PCDocente") {
            const opcion = document.createElement("option");
            opcion.value = equipo.id;
            opcion.textContent = equipo.nombre;
            nroPc.appendChild(opcion);
        }
    }

    if (equipoSeleccionado) {
        nroPc.value = equipoSeleccionado;
    }
}

function actualizarAlumno() {
    const seleccionada = nroPc.options[nroPc.selectedIndex];
    if (seleccionada && seleccionada.textContent === "PCDocente") {
        nombreAlumno.value = nombreUsuario;
    }
}


function actualizarObligatorios() {
    const esPC = tipoIncidencia.value === "PC";

    incidenciaGeneral.required = esPC;

    if (esPC) {
        actualizarDescripcion(incidenciaGeneral, descripcion, avisoDescripcion);
    } else {
        descripcion.required = true;
        avisoDescripcion.textContent = "(obligatoria)";
    }
}

function mostrarCampoExtra() {
    const esPC = tipoIncidencia.value === "PC";

    campoExtra.classList.toggle("d-none", !esPC);
    nroPc.required = esPC;

    if (esPC) {
        cargarEquipos(null);
        actualizarAlumno();
    } else {
        nroPc.replaceChildren();
        nombreAlumno.value = "";
        incidenciaGeneral.value = "";
    }

    actualizarObligatorios();
}

function abrirModificar(incidencia) {
    idEnEdicion = incidencia.id;
    espacioEnEdicion = incidencia.tipoEspacio;

    tipoIncidencia.value = incidencia.tipoIncidencia;
    descripcion.value = incidencia.descripcionIncidencia;
    incidenciaGeneral.value = incidencia.idIncidenciaGeneral ?? "";
    actualizarObligatorios();

    campoExtra.classList.toggle("d-none", incidencia.tipoIncidencia !== "PC");
    nroPc.required = incidencia.tipoIncidencia === "PC";

    if (incidencia.tipoIncidencia === "PC") {
        cargarEquipos(incidencia.idEquipo);
    } else {
        nroPc.replaceChildren();
    }

    nombreAlumno.value = incidencia.alumno ?? "";
    campoModificar.style.display = "block";
}

function cerrarModificar() {
    campoModificar.style.display = "none";
    formularioModificar.reset();
    actualizarObligatorios();
}

async function eliminarIncidencia(incidencia) {
    if (!window.confirm("¿Estás seguro de eliminar esta incidencia?")) {
        return;
    }

    try {
        const resultado = await api("DELETE", "incidencias.php", { idIncidencia: incidencia.id });
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

function agregarFila(incidencia) {
    const fila = document.createElement("tr");
    
    fila.dataset.estado = incidencia.tipoEstado;
    fila.dataset.tipo = incidencia.tipoIncidencia;
    fila.dataset.general = incidencia.nombreIncidenciaGeneral;

    celda(fila, incidencia.tipoIncidencia);
    celda(fila, incidencia.nombreIncidenciaGeneral);
    celda(fila, incidencia.tipoEspacio);
    celda(fila, incidencia.numeroEspacio);
    celda(fila, incidencia.nombreGrupo);
    celda(fila, incidencia.nombreEquipo);
    celda(fila, incidencia.alumno);
    celda(fila, incidencia.descripcionIncidencia);

    const campoEstado = document.createElement("td");
    const btnEstado = document.createElement("button");
    btnEstado.type = "button";
    btnEstado.textContent = "Ver estado";
    btnEstado.addEventListener("click", () => abrirVerEstado(incidencia));
    campoEstado.appendChild(btnEstado);
    fila.appendChild(campoEstado);

    celda(fila, formatearFecha(incidencia.fecha));

    const campoAcciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.addEventListener("click", () => abrirModificar(incidencia));

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.addEventListener("click", () => eliminarIncidencia(incidencia));

    campoAcciones.appendChild(btnModificar);
    campoAcciones.appendChild(btnEliminar);
    fila.appendChild(campoAcciones);

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const incidencias = await api("GET", "incidencias.php?rol=solicitante");

    for (const incidencia of incidencias) {
        agregarFila(incidencia);
    }
    aplicarFiltro();
    buscador();
    paginar();
}


async function modificarIncidencia(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("PUT", "incidencias.php", {
            idIncidencia: idEnEdicion,
            tipoIncidencia: tipoIncidencia.value,
            nroPc: nroPc.value,
            nombreAlumno: nombreAlumno.value,
            idIncidenciaGeneral: tipoIncidencia.value === "PC" ? incidenciaGeneral.value : "",
            descripcion: descripcion.value
        });

        cerrarModificar();
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

async function iniciar() {
    try {
        equipos = await api("GET", "equipos.php");
        await cargarIncidenciasGenerales(incidenciaGeneral);
        await cargarTabla();
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

tipoIncidencia.addEventListener("change", mostrarCampoExtra);
nroPc.addEventListener("change", actualizarAlumno);
incidenciaGeneral.addEventListener("change", actualizarObligatorios);
btnCerrarModificar.addEventListener("click", cerrarModificar);
formularioModificar.addEventListener("submit", modificarIncidencia);

iniciar();