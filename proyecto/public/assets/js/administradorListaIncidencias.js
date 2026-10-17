const cuerpoTabla = document.getElementById("cuerpoTabla");


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
}

cargarTabla().catch((error) => mostrarMensaje(error.message));