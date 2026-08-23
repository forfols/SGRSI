document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.getElementById("modificarUsuario");
    const campoNombre = document.getElementById("nombre");
    const campoCi = document.getElementById("ci");
    const campoRol = document.getElementById("rol");
    const formularioModificar = document.querySelector(".formularioModificarUsuario");
    const btnCerrar = document.getElementById("btnCerrarModificarUsuario");
    const estaActivo= document.getElementById("estaActivo");

    document.querySelectorAll(".btnModificar").forEach(function (boton) {

    boton.addEventListener("click", function () {

        campoNombre.value = boton.dataset.nombre;
        campoCi.value = boton.dataset.ci;
        estaActivo.value = boton.dataset.activo;

        document.getElementById("rolSolicitante").checked =
            boton.dataset.solicitante === "1";

        document.getElementById("rolTecnico").checked =
            boton.dataset.tecnico === "1";

        document.getElementById("rolAdministrador").checked =
            boton.dataset.administrador === "1";

        formularioModificar.style.display = "block";
    });

});

    btnCerrar.addEventListener("click", function () {
        formularioModificar.style.display = "none";
    });

});