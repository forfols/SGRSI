document.addEventListener("DOMContentLoaded", function () {
    const formulario = document.getElementById("registroIncidencia");

    const terminar = document.getElementById("btnTerminar");

    terminar.addEventListener("click", function () {
        localStorage.removeItem("registroEspacio");
        window.location.href = "indexSolicitante.php";

    });

    const select = document.getElementById("tipoIncidencia");
    const campoExtra = document.getElementById("campoExtra");
    const nroPC = document.getElementById("nroPC");

    select.addEventListener("change", function () {
        if (select.value === "PC") {
            campoExtra.classList.remove("d-none");
            nroPC.required = true;
        } else {
            campoExtra.classList.add("d-none");
            nroPC.required = false;
        }
    })

    formulario.addEventListener("submit", function (e) {
        e.preventDefault();

        const tipoIncidencia = document.getElementById("tipoIncidencia").value;
        let nroPC = document.getElementById("nroPC").value;
        let nombreAlumno = document.getElementById("nombreAlumno").value;
        const descripcion = document.getElementById("descripcion").value;
        let estado = "Sin asignar";
        let prioridad="Sin asignar";
        let tecnicoAsignado="Sin asignar";
        let diagnostico="N/A";
        let soluciones="N/A";

        const registroEspacio = JSON.parse(localStorage.getItem("registroEspacio")) || [];
        const datosEspacio = registroEspacio[0] || {};

        const usuarioUtilizado = JSON.parse(localStorage.getItem("usuarioUtilizado")) || [];
        const usuario = usuarioUtilizado[0] || {};

        let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];

        if (nroPC === "") {
            nroPC = "No se usó"
        }
        if (nombreAlumno === "") {
            nombreAlumno = "Sin asignar"
        }

        let contador = parseInt(localStorage.getItem("contadorIncidencia")) || 0;
        contador++;
        localStorage.setItem("contadorIncidencia", contador);

        const registroNuevaIncidencia = {
            id: contador,
            nombreDocente: usuario.usuario,
            ci: usuario.ci,
            tipoIncidencia,
            nroPC,
            nombreAlumno,
            descripcion,
            tipoEspacio: datosEspacio.tipoEspacio,
            nroEspacio: datosEspacio.nroEspacio,
            grupo: datosEspacio.grupo,
            fecha: datosEspacio.fecha,
            estado,
            prioridad,
            tecnicoAsignado,
            diagnostico,
            soluciones


        }
        registroIncidencia.push(registroNuevaIncidencia);
        localStorage.setItem("registroIncidencia", JSON.stringify(registroIncidencia));
        alert("Incidencia realizada, si desea terminar de realizar incidencias presione en Terminar");

        document.getElementById('nroPC').value = "";
        document.getElementById('nombreAlumno').value = "";
        document.getElementById('descripcion').value = "";


    })
})