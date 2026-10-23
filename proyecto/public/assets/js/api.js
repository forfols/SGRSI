const URL_API = "../index.php";
const INTERVALO_LATIDO = 30000;
const TIEMPO_INACTIVIDAD = 15 * 60 * 1000; // cierre forzoso

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


async function cerrarSesion() {
    try {
        await api("POST", "logout");
    } catch {
    }

    sessionStorage.clear();
    window.location.replace("inicioSesion.html");
}

let temporizadorInactividad;
let huboActividad = true;

function registrarActividad() {
    huboActividad = true;
    clearTimeout(temporizadorInactividad);
    temporizadorInactividad = setTimeout(cerrarSesion, TIEMPO_INACTIVIDAD);
}

if (sessionStorage.getItem("token")) {
    ["mousemove", "keydown", "click", "scroll", "touchstart"].forEach((evento) => {
        document.addEventListener(evento, registrarActividad, { passive: true });
    });

    registrarActividad();

    setInterval(() => {
        if (huboActividad) {
            huboActividad = false;
            api("POST", "latido").catch(() => {});
        }
    }, INTERVALO_LATIDO);
}