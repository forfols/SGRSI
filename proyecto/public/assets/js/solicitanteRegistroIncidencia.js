const parametros = new URLSearchParams(window.location.search);
const idRegistroEspacio = parametros.get("idRegistroEspacio");
const tipoEspacio = parametros.get("tipoEspacio");
const nombreUsuario = sessionStorage.getItem("nombre") ?? "";

if (!idRegistroEspacio || !tipoEspacio) {
    window.location.replace("solicitanteRegistroEspacio.html");
}

const formularioIncidencia = document.getElementById("registroIncidencia");
const tipo = document.getElementById("tipo");
const campoExtra = document.getElementById("campoExtra");
const nroPc = document.getElementById("nroPc");
const nombreAlumno = document.getElementById("nombreAlumno");
const incidenciaGeneral = document.getElementById("incidenciaGeneral");
const descripcion = document.getElementById("descripcion");
const avisoDescripcion = document.getElementById("avisoDescripcion");

let equipos = [];

function cargarEquipos() {
    nroPc.replaceChildren();

    //En un laboratorio se elige cualquier PC, en otros espacios solo la PC del docente
    for (const equipo of equipos) {
        if (tipoEspacio === "Laboratorio" || equipo.nombre === "PCDocente") {
            const opcion = document.createElement("option");
            opcion.value = equipo.id;
            opcion.textContent = equipo.nombre;
            nroPc.appendChild(opcion);
        }
    }

    actualizarAlumno();
}

function actualizarAlumno() {
    const seleccionada = nroPc.options[nroPc.selectedIndex];
    nombreAlumno.value = seleccionada && seleccionada.textContent === "PCDocente" ? nombreUsuario : "";
}

//Con PC: incidencia general obligatoria y descripción opcional (salvo "Otro").
//Con "Otros": no hay incidencia general, la descripción es obligatoria.
function actualizarObligatorios() {
    const esPC = tipo.value === "PC";

    incidenciaGeneral.required = esPC;

    if (esPC) {
        actualizarDescripcion(incidenciaGeneral, descripcion, avisoDescripcion);
    } else {
        descripcion.required = true;
        avisoDescripcion.textContent = "(obligatoria)";
    }
}

function cambiarTipo() {
    const esPC = tipo.value === "PC";

    campoExtra.classList.toggle("d-none", !esPC);
    nroPc.required = esPC;

    if (esPC) {
        cargarEquipos();
    } else {
        nroPc.replaceChildren();
        nombreAlumno.value = "";
        incidenciaGeneral.value = "";
    }

    actualizarObligatorios();
}

async function registrarIncidencia(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "incidencias.php", {
            idRegistroEspacio: idRegistroEspacio,
            tipo: tipo.value,
            nroPc: nroPc.value,
            nombreAlumno: nombreAlumno.value,
            idIncidenciaGeneral: incidenciaGeneral.value,
            descripcion: descripcion.value
        });

        mostrarMensaje(resultado.mensaje, "mensaje");
        formularioIncidencia.reset();
        cambiarTipo();
        actualizarDescripcion(incidenciaGeneral, descripcion, avisoDescripcion);
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

async function iniciar() {
    try {
        equipos = await api("GET", "equipos.php");
        await cargarIncidenciasGenerales(incidenciaGeneral);
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

tipo.addEventListener("change", cambiarTipo);
nroPc.addEventListener("change", actualizarAlumno);
incidenciaGeneral.addEventListener("change", () => actualizarDescripcion(incidenciaGeneral, descripcion, avisoDescripcion));
formularioIncidencia.addEventListener("submit", registrarIncidencia);

iniciar();