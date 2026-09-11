document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("registroIncidencia");

    const terminar = document.getElementById("btnTerminar");

    terminar.addEventListener("click", function () {
        window.location.href = "indexSolicitante.php";

    });

    const tipo = document.getElementById("tipo");
    const campoExtra = document.getElementById("campoExtra");
    const nroPc = document.getElementById("nroPc");
    const nombreAlumno= document.getElementById("nombreAlumno");

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

                    if (equipo.nombre == "PCDocente") {

                        nombreAlumno.value = alumnoAsignado;
                    }

                } else if (equipo.nombre == "PCDocente") {
                    const option = document.createElement("option");
                    option.value = equipo.id;
                    option.textContent = equipo.nombre;

                    selectPc.appendChild(option);

                    nombreAlumno.value = alumnoAsignado;

                }
            });


        } else {
            campoExtra.classList.add("d-none");
            nroPc.required = false;

            nroPc.value = "";
            if (document.getElementById("nombreAlumno")) {
                document.getElementById("nombreAlumno").value = "";
            }

        }
    })

    formulario.addEventListener("submit", function (e) {



    })

})