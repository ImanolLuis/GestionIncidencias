$(document).ready(function () {

    mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", "tipoIncidenciaStat");
    mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", "clienteStat");
    mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", "empleadoStat");
});

function mostrarDiagrama(url, canvas) {
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json"
    }).done(function (data) {
        if(data!=null)
        {
            let etiquetas=[];
            let datos=[];
            for(let i=0;i<data.length;i++)
            {
                etiquetas.push(data[i]["etiqueta"]);
                datos.push(data[i]["dato"]);
            }

            let ctx = document.getElementById(canvas);
            let myChart = new Chart(ctx, {
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
                options: {}
            });
        }
    });
}