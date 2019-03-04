$(document).ready(function () {
    $("#actualizarCliente").submit(function (event) {
        event.preventDefault();
        if(validarCliente()) {
            let data=$("#actualizarCliente").serialize();
            $.ajax({
                type : "POST",
                url : "index.php?controller=Cliente&action=update",
                data : data
            }).done(function () {
                mostrarModal("success", "Los datos del cliente se han actualizado correctamente en el servidor.");
            }).fail(function () {
                mostrarModal("danger", "Problemas al actualizar los datos del cliente en el servidor.");
            });
        } else {
            mostrarModal("danger", "Datos erróneos al actualizar el cliente");
        }
    });
    $("#actualizarTecnico").submit(function (event) {
        event.preventDefault();
        let data=$("#actualizarTecnico").serialize();
        $.ajax({
            type : "POST",
            url : "index.php?controller=Incidencia&action=updateTecnico",
            data : data
        }).done(function () {
            mostrarModal("success", "El técnico asignado se ha actualizado correctamente en el servidor.");
        }).fail(function () {
            mostrarModal("danger", "Problemas al actualizar el técnico asignado en el servidor.");
        });
    });
    $("#publicarAnotacion").submit(function (event) {
        event.preventDefault();
        if(validarAnotacion()) {
            let data=$("#publicarAnotacion").serialize();
            $.ajax({
                type : "POST",
                url : "index.php?controller=Anotacion&action=insert",
                data : data
            }).done(function () {
                cargarAnotaciones($("#idIncidencia"));
            }).fail(function () {
                mostrarModal("danger", "Problemas al publicar la anotación en el servidor.");
            });
        } else {
            mostrarModal("danger", "Datos erróneos al publicar la anotación");
        }
    });
    cargarAnotaciones($("#idIncidencia"));

    $("#refrescarAnotaciones").click(function() {
        cargarAnotaciones($("#idIncidencia"));
    });

    $("#cerrarIncidencia").click(function () {
        let incidencia=$("#idIncidencia");
        $.ajax({
            type : "POST",
            url : "index.php?action=cerrar",
            data : incidencia
        }).done(function () {
            cerrarIncidencia();
        }).fail(function () {
            mostrarModal("danger", "Problemas al cerrar la incidencia en el servidor.");
        });
    });
});

function validarCliente() {
    if(!$("#nombreCliente").val().trim()||!$("#apellidosCliente").val().trim()||!$("#empresaCliente").val().trim()||!$("#emailCliente").val().trim()||!$("#telefonoCliente").val().trim()) {
        return false;
    }
    if($("#nombreCliente").val().length>100||$("#apellidosCliente").val().length>200||$("#empresaCliente").val().length>100||$("#emailCliente").val().length>100||$("#telefonoCliente").val().length>15) {
        return false;
    }
    return true;
}

function validarAnotacion() {
    if(!$("#anotacion").val().trim()) {
        return false;
    }
    if($("#anotacion").val().length>255) {
        return false;
    }
    return true;
}

function cargarAnotaciones(incidencia) {
    $.ajax({
        type : "POST",
        url : "index.php?controller=Anotacion",
        data : incidencia,
        dataType : "JSON"
    }).done(function (anotaciones) {
        imprimirAnotaciones(anotaciones);
    }).fail(function () {
        mostrarModal("danger", "Problemas al cargar las anotaciones en el servidor.");
    });
}

function imprimirAnotaciones(anotaciones) {
    let anotacionesDiv=$("#anotaciones");

    anotacionesDiv.empty();

    // Construir fecha (array de meses)
    let months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    // Construir fecha (array de meses)

    for(let i=0;i<anotaciones.length;i++) {
        // Construrir fecha
        let date=new Date(anotaciones[i]["fecha"]);
        let fecha=date.getDate();
        fecha=fecha+" de "+months[date.getMonth()];
        fecha=fecha+" de "+date.getFullYear();
        // Construrir fecha

        anotacionesDiv.append("<blockquote class='blockquote'>\n" +
            "                                    <p class='mb-0'>" + anotaciones[i]['anotacion'] + "</p>\n" +
            "                                    <footer class='blockquote-footer'>" + anotaciones[i]['empleado'] + " &#124; " + fecha + "</footer>\n" +
            "                                </blockquote>");
    }
}

function cerrarIncidencia() {
    $("#cerrarIncidencia").remove();
    let estado=$("#estado");
    estado.val("Cerrada");
    estado.removeClass("w-50");
    estado.addClass("w-100");
}

function mostrarModal(tipo, mensaje) {
    let alert=$('#modal .alert');
    switch(tipo) {
        case "secondary":
            alert.addClass("alert-secondary");
            break;
        case "success":
            alert.addClass("alert-success");
            break;
        case "danger":
            alert.addClass("alert-danger");
            break;
        case "warning":
            alert.addClass("alert-warning");
            break;
        case "info":
            alert.addClass("alert-info");
            break;
        case "light":
            alert.addClass("alert-light");
            break;
        case "dark":
            alert.addClass("alert-dark");
            break;
        default:
            alert.addClass("alert-primary");
    }
    alert.empty();
    alert.append(mensaje);
    $('#modal').modal('show');
}