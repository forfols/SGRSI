document.getElementById("formLogin").addEventListener("submit", async function (e) {
    e.preventDefault();

    try {
        const datos = await api("POST", "login.php", {
            ci: document.getElementById("ci").value.trim(),
            contra: document.getElementById("contra").value
        });

        sessionStorage.setItem("csrfToken", datos.csrfToken);
        sessionStorage.setItem("roles", JSON.stringify(datos.roles));
        sessionStorage.setItem("nombre", datos.nombre);

        const r = datos.roles;
        const cantidad = [r.solicitante, r.tecnico, r.administrador].filter(Boolean).length;

        let destino = "indexGeneral.html";
        if (cantidad === 1) {
            if (r.solicitante) destino = "indexSolicitante.html";
            else if (r.tecnico) destino = "tecnico.html";
            else destino = "indexAdministrador.html";
        }

        window.location.replace(destino);
    } catch (error) {
        mostrarMensaje(error.message);
    }
});