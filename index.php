<?php

session_start();

if(isset($_SESSION["login"])) {
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
    if(isset($_POST["usuario"])) {
        $controllerObj->run("login");
    } else {
        $controllerObj->run("");
    }

}

function lanzarAccion($controllerObj) {
    if(isset($_GET["action"])) {
        $controllerObj->run($_GET["action"]);
    } else {
        $controllerObj->run("");
    }
}

?>