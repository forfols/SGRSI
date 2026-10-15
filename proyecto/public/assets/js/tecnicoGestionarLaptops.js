const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioAlta = document.getElementById("altaLaptop");
const modelo = document.getElementById("modelo");

async function darDeBaja(laptop) {
    const motivo = window.prompt("Motivo de la baja de " + laptop.modelo + " (ej: pantalla rota):");

    //Si cancela o deja el motivo vacío no se hace nada
    if (motivo === null || motivo.trim() === "") {
        return;
    }

    try {
        const resultado = await api("PUT", "laptops.php", { idLaptop: laptop.id, motivo: motivo.trim() });
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

function agregarFila(laptop) {
    const fila = document.createElement("tr");

    celda(fila, laptop.modelo);
    celda(fila, laptop.estado);

    const campoAcciones = document.createElement("td");

    //Una laptop prestada no se puede dar de baja: primero se devuelve
    if (laptop.estado === "Disponible") {
        const btnBaja = document.createElement("button");
        btnBaja.type = "button";
        btnBaja.textContent = "Dar de baja";
        btnBaja.addEventListener("click", () => darDeBaja(laptop));
        campoAcciones.appendChild(btnBaja);
    }

    fila.appendChild(campoAcciones);

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const laptops = await api("GET", "laptops.php");

    for (const laptop of laptops) {
        agregarFila(laptop);
    }
}

async function agregarLaptop(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "laptops.php", { modelo: modelo.value.trim() });

        formularioAlta.reset();
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

formularioAlta.addEventListener("submit", agregarLaptop);

cargarTabla().catch((error) => mostrarMensaje(error.message));