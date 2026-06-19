document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("tipoIncidencia");
    const campoExtra = document.getElementById("campoExtra");
    const nroPC = document.getElementById("nroPC");
    let idActual = null;

    const btnCerrarModificarIncidencia = document.getElementById("btnCerrarModificarIncidencia");

    btnCerrarModificarIncidencia.addEventListener("click", function () {
        document.querySelector(".formularioModificarIncidencia").style.display = "none";
    });

    select.addEventListener("change", function () {
        if (select.value === "PC") {
            campoExtra.classList.remove("d-none");
            nroPC.required = true;
        } else {
            campoExtra.classList.add("d-none");
            nroPC.required = false;
        }
    })





        function recargarTabla(){

        

        const tabla = document.getElementById('tabla');
        tabla.innerHTML = "";


        let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];

        const usuarioUtilizado = JSON.parse(localStorage.getItem("usuarioUtilizado")) || [];
        const usuario = usuarioUtilizado[0] || {};

        const incidenciasDocente = registroIncidencia.filter(function (filtro) {
            return filtro.ci === usuario.ci;
        });

        incidenciasDocente.forEach(function (datosIncidencia) {
            const fila = document.createElement("tr");


            let leerIncidencia = [
                {
                    tipoIncidencia: datosIncidencia.tipoIncidencia,
                    nroPC: datosIncidencia.nroPC,
                    nombreAlumno: datosIncidencia.nombreAlumno,
                    descripcion: datosIncidencia.descripcion,
                    tipoEspacio: datosIncidencia.tipoEspacio,
                    nroEspacio: datosIncidencia.nroEspacio,
                    grupo: datosIncidencia.grupo,
                    fecha: datosIncidencia.fecha,
                    estado: datosIncidencia.estado
                }]


            const casillaTipoIncidencia = document.createElement('td');
            casillaTipoIncidencia.textContent = datosIncidencia.tipoIncidencia;

            const casillaEspacio = document.createElement('td');
            casillaEspacio.textContent = datosIncidencia.tipoEspacio;

            const casillaGrupo = document.createElement('td');
            casillaGrupo.textContent = datosIncidencia.grupo;

            const casillaNroPC = document.createElement('td');
            casillaNroPC.textContent = datosIncidencia.nroPC;

            const casillaNombreAlumno = document.createElement('td');
            casillaNombreAlumno.textContent = datosIncidencia.nombreAlumno;

            const casillaDescripcion = document.createElement('td');
            casillaDescripcion.textContent = datosIncidencia.descripcion;

            const casillaNroEspacio = document.createElement('td');
            casillaNroEspacio.textContent = datosIncidencia.nroEspacio;

            const casillaEstado = document.createElement('td');
            casillaEstado.textContent = datosIncidencia.estado;

            const casillaFecha = document.createElement('td');
            casillaFecha.textContent = datosIncidencia.fecha;
            

            // Acciones

            const casillaAcciones = document.createElement('td');
            const article = document.createElement('article');
            article.className = 'acciones';

            const btnModificar = document.createElement('button');
            btnModificar.textContent = 'Modificar';
            

            btnModificar.addEventListener("click", function () {
                idActual = datosIncidencia.id;
                document.querySelector(".formularioModificarIncidencia").style.display = "block";

                document.getElementById("tipoEspacio").value = datosIncidencia.tipoEspacio;
                document.getElementById("nroEspacio").value = datosIncidencia.nroEspacio;
                document.getElementById("grupo").value = datosIncidencia.grupo;
                document.getElementById("tipoIncidencia").value = datosIncidencia.tipoIncidencia;
                document.getElementById("nroPC").value = datosIncidencia.nroPC;
                document.getElementById("nombreAlumno").value = datosIncidencia.nombreAlumno;
                document.getElementById("descripcion").value = datosIncidencia.descripcion;


            });

            document.getElementById("modificarIncidencia").addEventListener("submit", function (e) {
                e.preventDefault();

                let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];

                const incidencia = registroIncidencia.find(datosIncidencia => datosIncidencia.id === idActual);

                if (incidencia) {
                    incidencia.tipoEspacio = document.getElementById("tipoEspacio").value;
                    incidencia.nroEspacio = document.getElementById("nroEspacio").value;
                    incidencia.grupo = document.getElementById("grupo").value;
                    incidencia.tipoIncidencia = document.getElementById("tipoIncidencia").value;
                    incidencia.nroPC = document.getElementById("nroPC").value;
                    incidencia.nombreAlumno = document.getElementById("nombreAlumno").value;
                    incidencia.descripcion = document.getElementById("descripcion").value;
                }

                localStorage.setItem("registroIncidencia", JSON.stringify(registroIncidencia));
                document.querySelector(".formularioModificarIncidencia").style.display = "none";
                recargarTabla();
            });

            // Para que los objetos se vean
            fila.appendChild(casillaTipoIncidencia);
            fila.appendChild(casillaEspacio);
            fila.appendChild(casillaGrupo);
            fila.appendChild(casillaNroPC);
            fila.appendChild(casillaNombreAlumno);
            fila.appendChild(casillaDescripcion);
            fila.appendChild(casillaNroEspacio);
            fila.appendChild(casillaEstado);
            fila.appendChild(casillaFecha);

            fila.appendChild(casillaAcciones);
            casillaAcciones.appendChild(article);
            article.appendChild(btnModificar);

            tabla.appendChild(fila);

        })

    }

    recargarTabla();

    
})
