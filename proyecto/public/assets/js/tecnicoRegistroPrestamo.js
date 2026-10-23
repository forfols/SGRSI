const formularioPrestamo = document.getElementById("registroPrestamo");
const nombre = document.getElementById("nombre");
const apellido = document.getElementById("apellido");
const cedula = document.getElementById("cedula");
const laptop = document.getElementById("laptop");

//Carga solo las laptops que no están prestadas
async function cargarLaptops() {
    laptop.replaceChildren();

    const opcionInicial = document.createElement("option");
    opcionInicial.value = "";
    opcionInicial.textContent = "--";
    opcionInicial.disabled = true;
    opcionInicial.selected = true;
    opcionInicial.hidden = true;
    laptop.appendChild(opcionInicial);

    const laptops = await api("GET", "prestamos.php?disponibles=1");

    for (const item of laptops) {
        const opcion = document.createElement("option");
        opcion.value = item.id;
        opcion.textContent = item.modelo;
        laptop.appendChild(opcion);
    }
}

async function registrarPrestamo(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "prestamos.php", {
            nombre: nombre.value,
            apellido: apellido.value,
            cedula: cedula.value.trim(),
            idLaptop: laptop.value
        });

        mostrarMensaje(resultado.mensaje, "mensaje");
        formularioPrestamo.reset();
        //La laptop recién prestada desaparece del menú
        await cargarLaptops();
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

async function iniciar() {
    try {
        await cargarLaptops();
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

formularioPrestamo.addEventListener("submit", registrarPrestamo);

iniciar();