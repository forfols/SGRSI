const formularioEstado = document.getElementById("verEstado");
const campoEstado = document.querySelector(".formularioVerEstado");
const estado = document.getElementById("estado");
const tecnico = document.getElementById("tecnico");
const prioridad = document.getElementById("prioridad");
const diagnostico = document.getElementById("diagnostico");
const solucion = document.getElementById("solucion");
const campoTecnico = document.getElementById("campoTecnico");
const campoSolucion = document.getElementById("campoSolucion");
const btnCerrarEstado = document.getElementById("btnCerrarVerEstado");

document.querySelectorAll(".btnVerEstado").forEach(function (boton) {

    boton.addEventListener("click", function () {

        estado.textContent = boton.dataset.estado;
        prioridad.textContent = boton.dataset.prioridad;
        diagnostico.textContent = boton.dataset.diagnostico;

        if (boton.dataset.estado !== "Sin asignar") {
            tecnico.textContent = boton.dataset.tecnico;
            campoTecnico.style.display = "block";
        } else {
            campoTecnico.style.display = "none";
        }
        if (boton.dataset.estado === "Terminado") {
            solucion.textContent = boton.dataset.solucion;
            campoSolucion.style.display = "block";
        } else {
            campoSolucion.style.display = "none";
        }
        campoEstado.style.display = "block";
    });
});

btnCerrarEstado.addEventListener("click", function () {
    campoEstado.style.display = "none";
});

