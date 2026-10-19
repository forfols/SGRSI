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
    filtrarTabla();
}

function buscador(){
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const celdas = Array.from(fila.cells).slice(0, -1);
        const contenido = celdas.map(c => c.textContent).join('').toLowerCase();
        fila.style.display = contenido.includes(texto)|| texto === '' ? '' : 'none'; 
    });
}

document.getElementById('buscador').addEventListener('input', buscador);

cargarTabla().catch((error) => mostrarMensaje(error.message));