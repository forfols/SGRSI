const contenedor = document.getElementById("botonesRoles");
const roles = JSON.parse(sessionStorage.getItem("roles") ?? "{}");

const opciones = [
    ["solicitante", "indexSolicitante.html", "Ingresar como Solicitante"],
    ["tecnico", "tecnico.html", "Ingresar como Técnico"],
    ["administrador", "indexAdministrador.html", "Ingresar como Administrador"]
];

let tieneRol = false;

for (const [rol, destino, texto] of opciones) {
    if (roles[rol]) {
        tieneRol = true;

        const enlace = document.createElement("a");
        enlace.href = destino;

        const boton = document.createElement("button");
        boton.textContent = texto;

        enlace.appendChild(boton);
        contenedor.appendChild(enlace);
    }
}

if (!tieneRol) {
    const aviso = document.createElement("p");
    aviso.textContent = "Este usuario todavía no cuenta con un rol asignado";
    contenedor.appendChild(aviso);
}