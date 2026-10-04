const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioModificar = document.getElementById("modificarIncidencia");
const campoModificar = document.querySelector(".formularioModificarIncidencia");
const btnCerrarModificar = document.getElementById("btnCerrarModificarIncidencia");
const tipoIncidencia = document.getElementById("tipoIncidencia");
const campoExtra = document.getElementById("campoExtra");
const nroPc = document.getElementById("nroPc");
const nombreAlumno = document.getElementById("nombreAlumno");
const descripcion = document.getElementById("descripcion");
const nombreUsuario = sessionStorage.getItem("nombre") ?? "";

let equipos = [];
let idEnEdicion = 0;
let espacioEnEdicion = "";

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
    }
}

function abrirModificar(incidencia) {
    idEnEdicion = incidencia.id;
    espacioEnEdicion = incidencia.tipoEspacio;

    tipoIncidencia.value = incidencia.tipoIncidencia;
    descripcion.value = incidencia.descripcionIncidencia;

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

    celda(fila, incidencia.tipoIncidencia);
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
}

async function modificarIncidencia(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("PUT", "incidencias.php", {
            idIncidencia: idEnEdicion,
            tipoIncidencia: tipoIncidencia.value,
            nroPc: nroPc.value,
            nombreAlumno: nombreAlumno.value,
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
        await cargarTabla();
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

tipoIncidencia.addEventListener("change", mostrarCampoExtra);
nroPc.addEventListener("change", actualizarAlumno);
btnCerrarModificar.addEventListener("click", cerrarModificar);
formularioModificar.addEventListener("submit", modificarIncidencia);

iniciar();