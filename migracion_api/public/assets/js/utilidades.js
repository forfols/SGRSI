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
});