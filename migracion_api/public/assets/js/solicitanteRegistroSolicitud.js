const formularioSolicitud = document.getElementById("registroSolicitud");
const tipoServicio = document.getElementById("tipoServicio");
const tipoEspacio = document.getElementById("tipoEspacio");
const nroEspacio = document.getElementById("nroEspacio");
const grupo = document.getElementById("grupo");
const fecha = document.getElementById("fecha");
const descripcion = document.getElementById("descripcion");
const btnEnviar = document.getElementById("btnEnviar");

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

async function enviarSolicitud(evento) {
    evento.preventDefault();

    //Se deshabilita el botón mientras se envía para no mandar solicitudes repetidas
    btnEnviar.disabled = true;

    try {
        const resultado = await api("POST", "solicitudes.php", {
            tipoServicio: tipoServicio.value,
            tipoEspacio: tipoEspacio.value,
            nroEspacio: nroEspacio.value,
            grupo: grupo.value,
            fecha: fecha.value,
            descripcion: descripcion.value
        });

        mostrarMensaje(resultado.mensaje, "mensaje");
        formularioSolicitud.reset();
        nroEspacio.replaceChildren();
    } catch (error) {
        mostrarMensaje(error.message);
    } finally {
        btnEnviar.disabled = false;
    }
}

tipoEspacio.addEventListener("change", cargarEspacios);
formularioSolicitud.addEventListener("submit", enviarSolicitud);

iniciar();