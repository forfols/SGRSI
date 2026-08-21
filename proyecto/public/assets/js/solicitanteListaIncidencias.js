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

const formularioModificar = document.getElementById("modificarIncidencia");
const campoModificar = document.querySelector(".formularioModificarIncidencia");
const btnCerrarModificarIncidencia = document.getElementById("btnCerrarModificarIncidencia");



document.querySelectorAll(".btnModificar").forEach(function (boton) {

    boton.addEventListener("click", function () {

        const idIncidencia = this.dataset.id;
        const estadoIncidencia=this.dataset.estado;


        campoModificar.style.display = "block";

        document.getElementById("idIncidenciaModificar").value = idIncidencia;
        document.getElementById("estadoModificar").value = estadoIncidencia;

        const tipoIncidencia = document.getElementById("tipoIncidencia");
        const campoExtra = document.getElementById("campoExtra");
        const nroPc = document.getElementById("nroPc");
        const descripcion = document.getElementById("descripcion");

        tipoIncidencia.addEventListener("change", function () {
            if (tipoIncidencia.value === "PC") {
                campoExtra.classList.remove("d-none");
                nroPc.required = true;
            } else {
                campoExtra.classList.add("d-none");
                nroPc.required = false;
            }
        })

    });
});

btnCerrarModificarIncidencia.addEventListener("click", function () {
    campoModificar.style.display = "none";
});


const formularioEliminar = document.getElementById("eliminarIncidencia");

document.querySelectorAll(".btnEliminar").forEach(function (boton) {

    boton.addEventListener("click", function () {

        const confirmar = confirm(
            "¿Estás seguro de eliminar esta incidencia?"
        );

        if (!confirmar) {
            return;
        }

        const idIncidencia = this.dataset.id;
        const estadoIncidencia = this.dataset.estado;

        document.getElementById("idIncidenciaEliminar").value = idIncidencia;
        document.getElementById("estadoEliminar").value = estadoIncidencia;

        formularioEliminar.submit();
    });
});
