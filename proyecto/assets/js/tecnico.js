document.addEventListener("DOMContentLoaded", function () {
    const select = document.getElementById("estado");
    const campoExtra = document.getElementById("campoExtra");
    const soluciones = document.getElementById("soluciones");
    let idActual = null;

    const btnCerrarModificarEstado = document.getElementById("btnCerrarModificarEstado");
    const btnCerrarVerEstado = document.getElementById("btnCerrarVerEstado");

    btnCerrarModificarEstado.addEventListener("click", function () {
        document.querySelector(".formularioModificarEstado").style.display = "none";
    });

    btnCerrarVerEstado.addEventListener("click", function () {
        document.querySelector(".formularioVerEstado").style.display = "none";
    });

    select.addEventListener("change", function () {
        if (select.value === "Terminado") {
            campoExtra.classList.remove("d-none");
            soluciones.required = true;
        } else {
            campoExtra.classList.add("d-none");
            soluciones.required = false;
        }
    })





    function recargarTabla() {



        const tabla = document.getElementById('tabla');
        tabla.innerHTML = "";


        let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];

        const usuarioUtilizado = JSON.parse(localStorage.getItem("usuarioUtilizado")) || [];
        const usuarioTecnico = usuarioUtilizado[0] || {};

        registroIncidencia.forEach(function (datosIncidencia) {
            const fila = document.createElement("tr");

            const casillaUsuario = document.createElement('td');
            casillaUsuario.textContent = datosIncidencia.nombreDocente;

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
            const articleEstado = document.createElement('article');
            articleEstado.className = 'estado';
            const btnVer = document.createElement('button');
            btnVer.textContent = 'Ver';


            const casillaFecha = document.createElement('td');
            casillaFecha.textContent = datosIncidencia.fecha;


            // Acciones

            const casillaAcciones = document.createElement('td');
            const article = document.createElement('article');
            article.className = 'acciones';

            const btnSeleccionar = document.createElement('button');
            btnSeleccionar.textContent = 'Seleccionar';


            btnVer.addEventListener("click", function () {
                idActual = datosIncidencia.id;
                document.querySelector(".formularioVerEstado").style.display = "block";

                document.getElementById("estadoVer").value = datosIncidencia.estado;
                document.getElementById("prioridadVer").value = datosIncidencia.prioridad;
                document.getElementById("diagnosticoVer").value = datosIncidencia.diagnostico;
                document.getElementById("solucionesVer").value = datosIncidencia.soluciones;


            });


            btnSeleccionar.addEventListener("click", function () {
                idActual = datosIncidencia.id;
                document.querySelector(".formularioModificarEstado").style.display = "block";

                document.getElementById("estado").value = datosIncidencia.estado;
                document.getElementById("prioridad").value = datosIncidencia.prioridad;
                document.getElementById("diagnostico").value = datosIncidencia.diagnostico;
                document.getElementById("soluciones").value = datosIncidencia.soluciones;


            });

            document.getElementById("modificarEstado").addEventListener("submit", function (e) {
                e.preventDefault();

                let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];

                const incidencia = registroIncidencia.find(datosIncidencia => datosIncidencia.id === idActual);

                if (incidencia) {
                    incidencia.estado = document.getElementById("estado").value;
                    incidencia.prioridad = document.getElementById("prioridad").value;
                    incidencia.diagnostico = document.getElementById("diagnostico").value;
                    incidencia.soluciones = document.getElementById("soluciones").value;
                }

                localStorage.setItem("registroIncidencia", JSON.stringify(registroIncidencia));
                document.querySelector(".formularioModificarEstado").style.display = "none";
                document.querySelector(".formularioVerEstado").style.display = "none";
                recargarTabla();
            });

            // Para que los objetos se vean
            fila.appendChild(casillaUsuario);
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
            article.appendChild(btnSeleccionar);

            casillaEstado.appendChild(articleEstado);
            articleEstado.appendChild(btnVer);

            tabla.appendChild(fila);

        })

    }

    recargarTabla();


})