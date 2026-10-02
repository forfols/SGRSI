const tipoEspacio = document.getElementById("tipoEspacio");
const nroEspacio = document.getElementById("nroEspacio");

function cargarEspacios() {

    nroEspacio.innerHTML = "";

    const opcionInicial = document.createElement("option");

    opcionInicial.value = "";
    opcionInicial.textContent = "Seleccione un número";

    nroEspacio.appendChild(opcionInicial);


    espacios.forEach(function(espacio) {

    if (espacio.tipo === tipoEspacio.value) {

        const opcion = document.createElement("option");

        opcion.value = espacio.numero;
        opcion.textContent = espacio.numero;

        nroEspacio.appendChild(opcion);
    }

});

}

tipoEspacio.addEventListener("change", cargarEspacios);