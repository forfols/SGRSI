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
        fila.classList.toggle('d-none', !visible);
    });

    const total =  document.querySelectorAll('.filtro:checked').length;
    document.getElementById('btn-filtrar').textContent = total ? `Filtrar (${total})` : 'Filtrar';
    
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('filtro')) aplicarFiltro();
})


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

estado.addEventListener("change", actualizarCampoSoluciones);
btnCerrar.addEventListener("click", cerrarModificarEstado);
formularioEstado.addEventListener("submit", modificarEstado);

cargarTabla().catch((error) => mostrarMensaje(error.message));