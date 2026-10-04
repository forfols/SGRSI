const URL_API = "../api";

async function api(metodo, recurso, cuerpo) {
    const headers = { "Content-Type": "application/json" };
    const token = sessionStorage.getItem("csrfToken");
    if (token) {
        headers["X-CSRF-Token"] = token;
    }

    const respuesta = await fetch(URL_API + "/" + recurso, {
        method: metodo,
        headers: headers,
        body: cuerpo ? JSON.stringify(cuerpo) : undefined
    });

    const texto = await respuesta.text();

    let json;
    try {
        json = JSON.parse(texto);
    } catch {
        throw new Error(`HTTP ${respuesta.status}: La API no devolvió JSON.`);
    }

    //Sesión no iniciada: vuelve al inicio de sesión (salvo que el 401 venga del propio login)
    if (respuesta.status === 401 && !recurso.startsWith("login.php")) {
        sessionStorage.clear();
        window.location.replace("inicioSesion.html");
        throw new Error(json.mensaje ?? "Sesión no iniciada");
    }

    if (!respuesta.ok) {
        throw new Error(json.mensaje ?? "La solicitud no se pudo completar.");
    }

    return json.datos;
}

async function cerrarSesion() {
    try {
        await api("POST", "logout.php");
    } catch (error) {
        mostrarMensaje(error.message);
        return;
    }

    sessionStorage.clear();
    window.location.replace("inicioSesion.html");
}