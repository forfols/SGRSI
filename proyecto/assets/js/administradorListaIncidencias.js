document.addEventListener("DOMContentLoaded", function () {

        const btnCerrarVerEstado = document.getElementById("btnCerrarVerEstado");

        btnCerrarVerEstado.addEventListener("click", function () {
        document.querySelector(".formularioVerEstado").style.display = "none";
    });


        function recargarTabla(){

        

        const tabla = document.getElementById('tabla');
        tabla.innerHTML = "";


        let registroIncidencia = JSON.parse(localStorage.getItem("registroIncidencia")) || [];


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


            btnVer.addEventListener("click", function () {
                idActual = datosIncidencia.id;
                document.querySelector(".formularioVerEstado").style.display = "block";

                document.getElementById("estadoVer").value = datosIncidencia.estado;
                document.getElementById("prioridadVer").value = datosIncidencia.prioridad;
                document.getElementById("diagnosticoVer").value = datosIncidencia.diagnostico;
                document.getElementById("solucionesVer").value = datosIncidencia.soluciones;


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

            casillaEstado.appendChild(articleEstado);
            articleEstado.appendChild(btnVer);

            tabla.appendChild(fila);

        })

    }

    recargarTabla();

    
})
