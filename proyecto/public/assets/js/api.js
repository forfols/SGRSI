const URL_API = "../index.php";
const INTERVALO_LATIDO = 30000;

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

//El servidor libera la presencia y el cliente descarta el token
async function cerrarSesion() {
    try {
        await api("POST", "logout");
    } catch {
        // si falla (token vencido, sin red), igual se cierra del lado del cliente
    }

    sessionStorage.clear();
    window.location.replace("inicioSesion.html");
}

//Latido: avisa cada 30 s que la pestaña sigue abierta
if (sessionStorage.getItem("token")) {
    setInterval(() => {
        api("POST", "latido").catch(() => {});
    }, INTERVALO_LATIDO);
}

const TIEMPO_INACTIVIDAD = 15 * 60 * 1000; // 15 minutos
let temporizadorInactividad;

function reiniciarInactividad() {
    clearTimeout(temporizadorInactividad);
    temporizadorInactividad = setTimeout(cerrarSesion, TIEMPO_INACTIVIDAD);
}

if (sessionStorage.getItem("token")) {
    ["mousemove", "keydown", "click", "scroll", "touchstart"].forEach((evento) => {
        document.addEventListener(evento, reiniciarInactividad, { passive: true });
    });

    reiniciarInactividad();
}