//Colores del sistema para mantener la identidad visual
const PALETA = ["#CD1A30", "#FEE785", "#7A0C31", "#E8E8E8", "#FF6B6B", "#B8860B"];

//Los textos y líneas por defecto de Chart.js son oscuros, el fondo del sistema también
Chart.defaults.color = "#ffffff";
Chart.defaults.borderColor = "rgba(255, 255, 255, 0.15)";

const graficos = {};

function etiquetas(filas) {
    return filas.map((fila) => fila.etiqueta);
}

function totales(filas) {
    //MySQL puede devolver COUNT como texto, se pasa a número
    return filas.map((fila) => Number(fila.total));
}

function crearGrafico(idCanvas, tipo, filas, titulo, horizontal = false) {
    //Si ya existe un gráfico en ese canvas se destruye antes de crear otro
    if (graficos[idCanvas]) {
        graficos[idCanvas].destroy();
    }

    const esCircular = tipo === "doughnut" || tipo === "pie";

    graficos[idCanvas] = new Chart(document.getElementById(idCanvas), {
        type: tipo,
        data: {
            labels: etiquetas(filas),
            datasets: [{
                label: titulo,
                data: totales(filas),
                backgroundColor: esCircular ? PALETA : "#CD1A30",
                borderColor: tipo === "line" ? "#FEE785" : "#1c0905",
                borderWidth: tipo === "line" ? 3 : 1,
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            indexAxis: horizontal ? "y" : "x",
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: esCircular }
            },
            scales: esCircular ? {} : {
                [horizontal ? "x" : "y"]: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
}

async function cargarMetricas() {
    try {
        const metricas = await api("GET", "metricas.php");

        crearGrafico("graficoEstado", "doughnut", metricas.porEstado, "Incidencias");
        crearGrafico("graficoTipo", "doughnut", metricas.porTipo, "Incidencias");
        crearGrafico("graficoEspacio", "bar", metricas.porEspacio, "Incidencias");
        crearGrafico("graficoTecnico", "bar", metricas.porTecnico, "Incidencias");
        crearGrafico("graficoMes", "line", metricas.porMes, "Incidencias");
        crearGrafico("graficoSalon", "bar", metricas.porSalon, "Incidencias");
        crearGrafico("graficoMes", "line", metricas.porMes, "Incidencias");
        crearGrafico("graficoSalon", "bar", metricas.porSalon, "Incidencias", true);
    } catch (error) {
        mostrarMensaje(error.message);
    }
}

cargarMetricas();