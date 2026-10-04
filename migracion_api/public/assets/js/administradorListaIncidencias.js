const cuerpoTabla = document.getElementById("cuerpoTabla");

function agregarFila(incidencia) {
    const fila = document.createElement("tr");

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
}

cargarTabla().catch((error) => mostrarMensaje(error.message));