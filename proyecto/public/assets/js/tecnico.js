document.addEventListener("DOMContentLoaded", function () {

    const formularioModificarEstado = document.getElementById("modificarEstado");
    const campoModificarEstado = document.querySelector(".formularioModificarEstado");
    const btnCerrarModificarEstado = document.getElementById("btnCerrarModificarEstado");
    const estado = document.getElementById("estado");
    const prioridad = document.getElementById("prioridad");
    const diagnostico = document.getElementById("diagnostico");
    const soluciones = document.getElementById("soluciones");
    const campoExtra = document.getElementById("campoExtra");


    document.querySelectorAll(".btnModificarEstado")
        .forEach(function (boton) {

            boton.addEventListener("click", function () {
                const idIncidencia = this.dataset.id;
                const estadoActual = this.dataset.estado;
                const prioridadActual = this.dataset.prioridad;

                const diagnosticoActual = this.dataset.diagnostico;
                const solucionesActual =this.dataset.soluciones;


                document.getElementById("idIncidenciaEstado").value = idIncidencia;

                estado.value = estadoActual;
                prioridad.value = prioridadActual;
                diagnostico.value = diagnosticoActual;
                soluciones.value = solucionesActual;


                if (estadoActual === "Terminado") {
                campoExtra.classList.remove("d-none");
                soluciones.required = true;
                } else {
                    campoExtra.classList.add("d-none");
                    soluciones.required = false;
                }


                campoModificarEstado.style.display = "block";

            });

        });


    estado.addEventListener("change", function () {

        if (estado.value === "Terminado") {

            campoExtra.classList.remove("d-none");
            soluciones.required = true;
        } else {
            campoExtra.classList.add("d-none");
            soluciones.required = false;
        }

    });


    btnCerrarModificarEstado.addEventListener("click", function () {
            campoModificarEstado.style.display = "none";

        }
    );

});