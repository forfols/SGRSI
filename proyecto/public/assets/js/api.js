const URL_API = "../index.php";

async function api(metodo, recurso, cuerpo) {
    const [nombre, consulta] = recurso.split("?");
    const ruta = nombre.replace(".php", "");
    const url = URL_API + "?ruta=" + ruta + (consulta ? "&" + consulta : "");

    const headers = { "Content-Type": "application/json" };
    const token = sessionStorage.getItem("token");
    if (token) {
        headers["Authorization"] = "Bearer " + token;
    }

    const respuesta = await fetch(url, {
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

    //Token ausente, inválido o vencido: vuelve al inicio de sesión (salvo en el propio login)
    if (respuesta.status === 401 && ruta !== "login") {
        sessionStorage.clear();
        window.location.replace("inicioSesion.html");
        throw new Error(json.mensaje ?? "Sesión no iniciada");
    }

    if (!respuesta.ok) {
        throw new Error(json.mensaje ?? "La solicitud no se pudo completar.");
    }

    return json.datos;
}

//Sin estado: el servidor no guarda la sesión, cerrarla es descartar el token
function cerrarSesion() {
    sessionStorage.clear();
    window.location.replace("inicioSesion.html");
}