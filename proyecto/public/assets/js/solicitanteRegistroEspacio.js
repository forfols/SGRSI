const tipoEspacio = document.getElementById("tipoEspacio");
const nroEspacio = document.getElementById("nroEspacio");
const grupo = document.getElementById("grupo");
const formularioEspacio = document.getElementById("registroEspacio");

let espacios = [];

function cargarEspacios() {
    nroEspacio.replaceChildren();

    const opcionInicial = document.createElement("option");
    opcionInicial.value = "";
    opcionInicial.textContent = "Seleccione un número";
    nroEspacio.appendChild(opcionInicial);

    for (const espacio of espacios) {
        if (espacio.tipo === tipoEspacio.value) {
            const opcion = document.createElement("option");
            opcion.value = espacio.numero;
            opcion.textContent = espacio.numero;
            nroEspacio.appendChild(opcion);
        }
    }
}

async function iniciar() {
    try {
        espacios = await api("GET", "espacios.php");
        const grupos = await api("GET", "grupos.php");

        for (const g of grupos) {
            const opcion = document.createElement("option");
            opcion.value = g.nombre;
            opcion.textContent = g.nombre;
            grupo.appendChild(opcion);
        }
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

async function registrarEspacio(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "espacios.php", {
            tipoEspacio: tipoEspacio.value,
            nroEspacio: nroEspacio.value,
            grupo: grupo.value
        });

        window.location.href = "solicitanteRegistroIncidencias.html?idRegistroEspacio=" + encodeURIComponent(resultado.idRegistroEspacio) + "&tipoEspacio=" + encodeURIComponent(tipoEspacio.value);
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

tipoEspacio.addEventListener("change", cargarEspacios);
formularioEspacio.addEventListener("submit", registrarEspacio);

iniciar();