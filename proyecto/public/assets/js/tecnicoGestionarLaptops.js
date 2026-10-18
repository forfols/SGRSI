const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioAlta = document.getElementById("altaLaptop");
const modelo = document.getElementById("modelo");

async function darDeBaja(laptop) {
    const confirmar = window.confirm(
        "¿Estás seguro que queres dar de baja la laptop?"
    );
    if (!confirmar) {
        return;
    }
    try {
        const resultado = await api("PUT", "laptops.php", {
            idLaptop: laptop.id
        });

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
    filtrarTabla();
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

function filtrarTabla(){
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const laptop = fila.cells[0]?.textContent.toLowerCase() ?? ''; 
        fila.style.display = laptop.includes(texto)|| texto === '' ? '' : 'none'; 
    });
}

document.getElementById('buscador').addEventListener('input', filtrarTabla);

cargarTabla().catch((error) => mostrarMensaje(error.message));