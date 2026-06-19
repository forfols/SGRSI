document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.getElementById("registroEspacio");

    formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    const tipoEspacio = document.getElementById("tipoEspacio").value;
    const nroEspacio = document.getElementById("nroEspacio").value;
    const grupo = document.getElementById("grupo").value;


    const fecha = new Date().toLocaleString();
    const registroEspacio = [
    {
        tipoEspacio,
        nroEspacio,
        grupo,
        fecha
    }]
    localStorage.setItem("registroEspacio", JSON.stringify(registroEspacio));
    
    window.location.href = "solicitanteRegistroIncidencias.html";



    })
})