$(document).ready(function () {

    let tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", {}, "tipoIncidenciaStat");
    let clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", {}, "clienteStat");
    let empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", {}, "empleadoStat");

    $("#prioridadCategoria").submit(function (event) {
        event.preventDefault();
        let data=$("#prioridadCategoria").serialize();
        tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", data, "tipoIncidenciaStat");
    });
    $("#estadoCategoria").submit(function (event) {
        event.preventDefault();
        let data=$("#estadoCategoria").serialize();
        tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", data, "tipoIncidenciaStat");
    });
    $("#fechaCategoria").submit(function (event) {
        event.preventDefault();
        let data=$("#fechaCategoria").serialize();
        tipoIncidenciaStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=categoria", data, "tipoIncidenciaStat");
    });

    $("#prioridadCliente").submit(function (event) {
        event.preventDefault();
        let data=$("#prioridadCliente").serialize();
        clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", data, "clienteStat");
    });
    $("#estadoCliente").submit(function (event) {
        event.preventDefault();
        let data=$("#estadoCliente").serialize();
        clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", data, "clienteStat");
    });
    $("#fechaCliente").submit(function (event) {
        event.preventDefault();
        let data=$("#fechaCliente").serialize();
        clienteStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=cliente", data, "clienteStat");
    });

    $("#prioridadEmpleado").submit(function (event) {
        event.preventDefault();
        let data=$("#prioridadEmpleado").serialize();
        empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", data, "empleadoStat");
    });
    $("#estadoEmpleado").submit(function (event) {
        event.preventDefault();
        let data=$("#estadoEmpleado").serialize();
        empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", data, "empleadoStat");
    });
    $("#fechaEmpleado").submit(function (event) {
        event.preventDefault();
        let data=$("#fechaEmpleado").serialize();
        empleadoStat=mostrarDiagrama("index.php?action=getEstadisticas&tipo=empleado", data, "empleadoStat");
    });
});

function mostrarDiagrama(url, dataForm, canvas) {
    $.ajax({
        type: "POST",
        url: url,
        data: dataForm,
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
                options: {}
            });
        }
    });
}