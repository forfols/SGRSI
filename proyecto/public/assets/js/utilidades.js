function celda(fila, texto) {
    const td = document.createElement("td");
    td.textContent = texto ?? "";
    fila.appendChild(td);
}

function formatearFecha(fecha) {
    return new Date(String(fecha).replace(" ", "T")).toLocaleString("es-UY", { dateStyle: "short", timeStyle: "short" });
}

//Muestra un aviso en <div id="alerta">. tipo: "alerta" (rojo) o "mensaje" (verde)
function mostrarMensaje(texto, tipo = "alerta") {
    const caja = document.getElementById("alerta");
    if (!caja) {
        window.alert(texto);
        return;
    }
    caja.className = tipo;
    caja.textContent = texto;
}

function abrirVerEstado(incidencia) {
    const el = (id) => document.getElementById(id);

    el("estado").textContent = incidencia.tipoEstado;
    el("prioridad").textContent = incidencia.prioridad;
    el("diagnostico").textContent = incidencia.diagnostico;
    el("tecnico").textContent = incidencia.nombreTecnico ?? "";
    el("campoTecnico").style.display = incidencia.tipoEstado !== "Sin asignar" ? "block" : "none";
    el("solucion").textContent = incidencia.soluciones;
    el("campoSolucion").style.display = incidencia.tipoEstado === "Terminado" ? "block" : "none";
    document.querySelector(".formularioVerEstado").style.display = "block";
}

//Carga el menú desplegable de incidencias generales, dejando "Otro" al final
async function cargarIncidenciasGenerales(select) {
    const lista = await api("GET", "incidencias.php?general=1");

    lista.sort((a, b) => (a.nombre === "Otro") - (b.nombre === "Otro") || a.nombre.localeCompare(b.nombre));

    for (const item of lista) {
        const opcion = document.createElement("option");
        opcion.value = item.id;
        opcion.textContent = item.nombre;
        select.appendChild(opcion);
    }
}

//La descripción es obligatoria solo si se elige "Otro"
function actualizarDescripcion(select, textarea, aviso) {
    const opcion = select.options[select.selectedIndex];
    const esOtro = opcion !== undefined && opcion.textContent === "Otro";

    textarea.required = esOtro;
    aviso.textContent = esOtro ? "(obligatoria)" : "(opcional)";
}

document.addEventListener("DOMContentLoaded", function () {
    const btnCerrarSesion = document.getElementById("btnCerrarSesion");
    if (btnCerrarSesion) {
        btnCerrarSesion.addEventListener("click", cerrarSesion);
    }

    const btnCerrarVerEstado = document.getElementById("btnCerrarVerEstado");
    if (btnCerrarVerEstado) {
        btnCerrarVerEstado.addEventListener("click", function () {
            document.querySelector(".formularioVerEstado").style.display = "none";
        });
    }

    //El botón "Cambiar rol" solo se muestra si el usuario tiene más de un rol
    const btnCambiarRol = document.getElementById("btnCambiarRol");
    if (btnCambiarRol) {
        const roles = JSON.parse(sessionStorage.getItem("roles") ?? "{}");

        if (Object.values(roles).filter(Boolean).length > 1) {
            btnCambiarRol.hidden = false;
        }
    }
});