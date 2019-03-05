<?php
/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * FrontController
 *
 * Si entra por primera vez se muestra la pantalla de inicio de sesión.
 * Si ha iniciado sesión o está iniciando sesión, lanza la acción correpondiente en el controlador ccorrepondiente.
 */
session_start();

if(isset($_SESSION["login"])||isset($_POST["usuario"])) {
    if(isset($_GET["controller"])) {
        switch ($_GET["controller"]) {
            case 'Empleado':
                require_once 'controller/EmpleadoController.php';
                $controllerObj=new EmpleadoController();
                break;
            case 'Cliente':
                require_once 'controller/ClienteController.php';
                $controllerObj=new ClienteController();
                break;
            case 'Anotacion':
                require_once 'controller/AnotacionController.php';
                $controllerObj=new AnotacionController();
                break;
            default:
                require_once 'controller/IncidenciaController.php';
                $controllerObj=new IncidenciaController();
        }
        lanzarAccion($controllerObj);
    } else {
        require_once 'controller/IncidenciaController.php';
        $controllerObj=new IncidenciaController();
        lanzarAccion($controllerObj);
    }
} else {
    require_once 'controller/EmpleadoController.php';
    $controllerObj=new EmpleadoController();
    $controllerObj->run("");

}

/**
 * Función que recibe el controlador y lanza la acción correspondiente.
 * @param $controllerObj
 */
function lanzarAccion($controllerObj) {
    if(isset($_GET["action"])) {
        $controllerObj->run($_GET["action"]);
    } else {
        $controllerObj->run("");
    }
}

?>