/*!
 * Gestión de Incidencias v1.0.1
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Para mostrar las estadísticas en la página
 */

$(document).ready(function () {

    // Al cargar la página
    let tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", {}, "tipoIncidenciaStat");
    let clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", {}, "clienteStat");
    let empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", {}, "empleadoStat");

    // Al pulsar en los botones para filtrar
    $("#categoria").submit(function (event) {
        event.preventDefault();
        let data=$("#categoria").serialize();
        resetCanvas("tipoIncidencia");
        tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", data, "tipoIncidenciaStat");
    });
    $("#cliente").submit(function (event) {
        event.preventDefault();
        let data=$("#cliente").serialize();
        resetCanvas("cliente");
        clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", data, "clienteStat");
    });
    $("#empleado").submit(function (event) {
        event.preventDefault();
        let data=$("#empleado").serialize();
        resetCanvas("empleado");
        empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", data, "empleadoStat");
    });e
});

/**
 * Función para mostrar el diagrama en pantalla obteniendo los datos por una llamada AJAX
 * @param url
 * @param dataForm
 * @param canvas
 * @return Chart|null
 */
function mostrarDiagrama(url, dataForm, canvas) {
    $.ajax({
        type: "POST",
        url: url,
        data: dataForm,
        dataType: "json"
    }).done(function (data) {
        if(data.length>0) {
            let etiquetas=[];
            let datos=[];
            let valorMaximo=0;
            for(let i=0;i<data.length;i++) {
                etiquetas.push(data[i]["etiqueta"]);
                datos.push(data[i]["dato"]);
                if(data[i]["dato"]>valorMaximo)
                {
                    valorMaximo=data[i]["dato"];
                }
            }
            valorMaximo++;

            let ctx = document.getElementById(canvas);
            return new Chart(ctx, {
                // The type of chart we want to create
                type: 'bar',

                // The data for our dataset
                data: {
                    labels: etiquetas,
                    datasets: [{
                        label: "Número de incidencias",
                        data: datos,
                        borderWidth: 1,
                        backgroundColor: 'rgba(33, 150, 243, 0.65)',
                        borderColor: 'rgba(33, 150, 243, 1)'
                    }]
                },

                // Configuration options go here
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                suggestedMax: valorMaximo,
                                suggestedMin: 0
                            }
                        }]
                    }
                }
            });
        } else {
            return null;
        }
    });
}

/**
 * Función para resetear la etiqueta canvas para recargar los datos
 * @param id
 */
function resetCanvas(id) {
    switch(id) {
        case "cliente":
            $("#clienteStat").remove();
            $("#clienteStatContainer").append("<canvas id='clienteStat' width='400' height='200'></canvas>");
            break;
        case "empleado":
            $("#empleadoStat").remove();
            $("#empleadoStatContainer").append("<canvas id='empleadoStat' width='400' height='200'></canvas>");
            break;
        default:
            $("#tipoIncidenciaStat").remove();
            $("#tipoIncidenciaStatContainer").append("<canvas id='tipoIncidenciaStat' width='400' height='200'></canvas>");
            break;
    }
}