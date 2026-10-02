document.getElementById("formLogin").addEventListener("submit", async function (e) {
    e.preventDefault();
    const alerta = document.getElementById("alerta");
    alerta.textContent = "";
    alerta.className = "";

    try {
        const datos = await api("POST", "login.php", {
            ci: document.getElementById("ci").value,
            contra: document.getElementById("contra").value
        });

        const r = datos.roles;
        const cantidad = [r.solicitante, r.tecnico, r.administrador].filter(Boolean).length;
        let destino = "indexGeneral.php";
        if (cantidad === 1) {
            if (r.solicitante) destino = "indexSolicitante.php";
            else if (r.tecnico) destino = "tecnico.php";
            else destino = "indexAdministrador.php";
        }
        location.href = URL_PUBLIC + "/" + destino;
    } catch (error) {
        alerta.className = "alerta";
        alerta.textContent = error.message;
    }
});