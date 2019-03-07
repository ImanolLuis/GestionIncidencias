/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Eventos de la página del seguimiento de la incidencia
 */

$(document).ready(function () {
    // Actualizar cliente de la incidencia en la base de datos
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
    // Actualizar técnico asignado de la incidencia en la base de datos
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
    // Publicar anotación en la base de datos
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
    // Cargar anotaciones al cargar la página
    cargarAnotaciones($("#idIncidencia"));

    // Recargar anotaciones
    $("#refrescarAnotaciones").click(function() {
        cargarAnotaciones($("#idIncidencia"));
    });

    // Actualizar estado de la incidencia cerrando la incidencia en la base de datos
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

/**
 * Validar datos del cliente
 * @return {boolean}
 */
function validarCliente() {
    if(!$("#nombreCliente").val().trim()||!$("#apellidosCliente").val().trim()||!$("#empresaCliente").val().trim()||!$("#emailCliente").val().trim()||!$("#telefonoCliente").val().trim()) {
        return false;
    }
    if($("#nombreCliente").val().length>100||$("#apellidosCliente").val().length>200||$("#empresaCliente").val().length>100||$("#emailCliente").val().length>100||$("#telefonoCliente").val().length>15) {
        return false;
    }
    return true;
}

/**
 * Validar datos de la anotación
 * @return {boolean}
 */
function validarAnotacion() {
    if(!$("#anotacion").val().trim()) {
        return false;
    }
    if($("#anotacion").val().length>255) {
        return false;
    }
    return true;
}

/**
 * Llamada al servidor para obtener las anotaciones de la incidencia
 * @param incidencia
 */
function cargarAnotaciones(incidencia) {
    $.ajax({
        type : "POST",
        url : "index.php?controller=Anotacion",
        data : incidencia,
        dataType : "JSON"
    }).done(function (anotaciones) {
        imprimirAnotaciones(anotaciones);
        $("#anotacion").val("");
    }).fail(function () {
        mostrarModal("danger", "Problemas al cargar las anotaciones en el servidor.");
    });
}

/**
 * Imprimir las anotaciones en pantalla
 * @param anotaciones
 */
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

/**
 * Efectos gráficos al cerrar incidencia
 */
function cerrarIncidencia() {
    $("#cerrarIncidencia").remove();
    let estado=$("#estado");
    estado.val("Cerrada");
    estado.removeClass("w-50");
    estado.addClass("w-100");
}

/**
 * Mostrar mensaje al ususario
 * @param tipo
 * @param mensaje
 */
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