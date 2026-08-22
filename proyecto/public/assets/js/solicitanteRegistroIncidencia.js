document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("registroIncidencia");

    const terminar = document.getElementById("btnTerminar");

    terminar.addEventListener("click", function () {
        window.location.href = "indexSolicitante.php";

    });

    const tipo = document.getElementById("tipo");
    const campoExtra = document.getElementById("campoExtra");
    const nroPc = document.getElementById("nroPc");

    tipo.addEventListener("change", function () {
        if (tipo.value === "PC") {
            campoExtra.classList.remove("d-none");
            nroPc.required = true;

            const selectPc = document.getElementById("nroPc");
            

            equipos.forEach(equipo => {

                if (tipoEspacio == "Laboratorio") {

                    const option = document.createElement("option");
                    option.value = equipo.id;
                    option.textContent = equipo.nombre;
                    selectPc.appendChild(option);

                } else if (equipo.nombre == "PCDocente") {
                    const option = document.createElement("option");
                    option.value = equipo.id;
                    option.textContent = equipo.nombre;

                    selectPc.appendChild(option);
                }
            });


        } else {
            campoExtra.classList.add("d-none");
            nroPc.required = false;
        }
    })

    formulario.addEventListener("submit", function (e) {

        alert("Incidencia realizada, si desea terminar de realizar incidencias presione en Terminar");


    })

})