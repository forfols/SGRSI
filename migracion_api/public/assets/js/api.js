async function api(metodo, recurso, cuerpo) {
    const meta = document.querySelector('meta[name="csrf-token"]');
    const headers = { "Content-Type": "application/json" };
    if (meta) headers["X-CSRF-Token"] = meta.content;

    const respuesta = await fetch(URL_API + "/" + recurso, {
        method: metodo,
        headers: headers,
        body: cuerpo ? JSON.stringify(cuerpo) : undefined
    });

    let json = {};
    try { json = await respuesta.json(); } catch (e) {}
    if (!respuesta.ok) {
        throw new Error(json.mensaje || "Error " + respuesta.status);
    }
    return json.datos;
}

function celda(fila, texto) {
    const td = document.createElement("td");
    td.textContent = texto ?? "";
    fila.appendChild(td);
}

function formatearFecha(f) {
    return new Date(String(f).replace(" ", "T")).toLocaleString("es-UY", { dateStyle: "short", timeStyle: "short" });
}

function abrirVerEstado(inc) {
    const el = id => document.getElementById(id);
    el("estado").textContent = inc.tipoEstado;
    el("prioridad").textContent = inc.prioridad;
    el("diagnostico").textContent = inc.diagnostico;
    el("tecnico").textContent = inc.nombreTecnico ?? "";
    el("campoTecnico").style.display = inc.tipoEstado !== "Sin asignar" ? "block" : "none";
    el("solucion").textContent = inc.soluciones;
    el("campoSolucion").style.display = inc.tipoEstado === "Terminado" ? "block" : "none";
    document.querySelector(".formularioVerEstado").style.display = "block";
}

document.addEventListener("DOMContentLoaded", function () {
    const cerrar = document.getElementById("btnCerrarVerEstado");
    if (cerrar) {
        cerrar.addEventListener("click", function () {
            document.querySelector(".formularioVerEstado").style.display = "none";
        });
    }
});