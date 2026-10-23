const cuerpoTabla = document.getElementById("cuerpoTabla");
const formularioAlta = document.getElementById("altaLaptop");
const modelo = document.getElementById("modelo");

async function darDeBaja(laptop) {
    const confirmar = window.confirm(
        "¿Estás seguro que queres dar de baja la laptop?"
    );
    if (!confirmar) {
        return;
    }
    try {
        const resultado = await api("PUT", "laptops.php", {
            idLaptop: laptop.id
        });

        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

function agregarFila(laptop) {
    const fila = document.createElement("tr");

    fila.dataset.estado = laptop.estado;

    celda(fila, laptop.modelo);
    celda(fila, laptop.estado);

    const campoAcciones = document.createElement("td");

    //Una laptop prestada no se puede dar de baja: primero se devuelve
    if (laptop.estado === "Disponible") {
        const btnBaja = document.createElement("button");
        btnBaja.type = "button";
        btnBaja.textContent = "Dar de baja";
        btnBaja.addEventListener("click", () => darDeBaja(laptop));
        campoAcciones.appendChild(btnBaja);
    }

    fila.appendChild(campoAcciones);

    cuerpoTabla.appendChild(fila);
}

async function cargarTabla() {
    cuerpoTabla.replaceChildren();

    const laptops = await api("GET", "laptops.php");

    for (const laptop of laptops) {
        agregarFila(laptop);
    }
    aplicarFiltro();
    buscador();
    paginar();
}

async function agregarLaptop(evento) {
    evento.preventDefault();

    try {
        const resultado = await api("POST", "laptops.php", { modelo: modelo.value.trim() });

        formularioAlta.reset();
        await cargarTabla();
        mostrarMensaje(resultado.mensaje, "mensaje");
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

formularioAlta.addEventListener("submit", agregarLaptop);

function buscador(){
    const texto = document.getElementById('buscador').value.toLowerCase().trim();
    const filas = document.querySelectorAll('#cuerpoTabla tr');

    filas.forEach(fila => {
        const celdas = Array.from(fila.cells);
        const contenido = celdas.filter(c => !c.querySelector('button')).map(c => c.textContent).join(' ').toLowerCase();
        fila.classList.toggle('oculto-busqueda',texto !== '' && !contenido.includes(texto));
    });
}

document.getElementById('buscador').addEventListener('input', () => {
    buscador(); paginaActual = 1; paginar();
});

const FILAS_POR_PAGINA = 10;
let paginaActual = 1;

function paginar() {
    const filas = [...document.querySelectorAll('#cuerpoTabla tr')];

    const candidatas = filas.filter(f =>
        !f.classList.contains('oculto-filtro') &&
        !f.classList.contains('oculto-busqueda')
    );

    const totalPaginas = Math.max(1, Math.ceil(candidatas.length / FILAS_POR_PAGINA));
    paginaActual = Math.min(paginaActual, totalPaginas);
    const inicio = (paginaActual - 1) * FILAS_POR_PAGINA;
    const visibles = new Set(candidatas.slice(inicio, inicio + FILAS_POR_PAGINA));

    filas.forEach(f => f.classList.toggle('oculto-pagina', !visibles.has(f)));

    dibujarPaginador(totalPaginas)
}

function dibujarPaginador(totalPaginas){
    const paginador = document.getElementById('paginador')
    if (!paginador) return;

    let html = `<li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
        <button class="page-link" data-pagina="${paginaActual - 1}">Anterior</button></li>`;

    for (let i = 1; i <= totalPaginas; i++) {
        html +=    `<li class="page-item ${i === paginaActual ? 'active' : ''}">
            <button class="page-link" data-pagina="${i}">${i}</button></li>`;
    }

    html += `<li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
        <button class="page-link" data-pagina="${paginaActual + 1}">Siguiente</button></li>`;

    paginador.innerHTML = html;
}

document.addEventListener('click', e => {
    const boton = e.target.closest('[data-pagina]');
    if(boton) {
        paginaActual = Number(boton.dataset.pagina)
        paginar();
    }
});

function aplicarFiltro(){
    const porCampo ={};
    document.querySelectorAll('.filtro:checked').forEach(c =>{
        (porCampo[c.dataset.campo] ??= []).push(c.value);    
    });

    document.querySelectorAll('#cuerpoTabla tr').forEach(fila => {
    const visible = Object.entries(porCampo).every(([campo, valores]) => 
            valores.includes(fila.dataset[campo])
        );
        fila.classList.toggle('oculto-filtro', !visible);
    });

    const total =  document.querySelectorAll('.filtro:checked').length;
    document.getElementById('btn-filtrar').textContent = total ? `Filtrar (${total})` : 'Filtrar';
    
}

document.addEventListener('change', e => {
    if (e.target.classList.contains('filtro')) {
        aplicarFiltro(); paginaActual = 1; paginar();
    }
});

cargarTabla().catch((error) => mostrarMensaje(error.message));