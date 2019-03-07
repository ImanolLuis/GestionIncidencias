<?php
/*!
 * Gestión de Incidencias v1.0.1
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Class AnotacionController
 */
class AnotacionController {
    private $conectar, $conexion, $twig;

    /**
     * AnotacionController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Anotacion.php";
        require_once __DIR__ . "/../model/Empleado.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
    }

    /**
     * Función que dependiendo de la acción que reciba ejecuta la función correpondiente
     * @param string $accion
     */
    public function run($accion = "") {
        switch ($accion) {
            case "insert":
                $this->insert();
                break;
            default:
                $this->selectAll();
        }
    }

    /**
     * Función que selecciona todos las anotaciones de una incidencia en la base de datos
     */
    private function selectAll() {
        if(isset($_POST["idIncidencia"])) {
            $anotacion=new Anotacion($this->conexion);
            $anotacion->setIdIncidencia($_POST["idIncidencia"]);
            $anotaciones=$anotacion->selectAllAnotacionByIncidencia();
            for($i=0;$i<count($anotaciones);$i++) {
                $empleado=new Empleado($this->conexion);
                $anotaciones[$i]["empleado"]=$empleado->selectNombreApellidosEmpleado($anotaciones[$i]["idEmpleado"]);
            }
            header('Content-type: application/json');
            echo json_encode($anotaciones);
        }
    }

    /**
     * Función que inserta una anotación en la base de datos
     */
    private function insert() {
        if(isset($_POST["anotacion"])) {
            $anotacion=new Anotacion($this->conexion);
            $anotacion->setAnotacion($_POST["anotacion"]);
            $anotacion->setIdIncidencia($_POST["idIncidencia"]);
            $anotacion->setIdEmpleado($_POST["idEmpleado"]);
            $anotacion->insert();
        }
    }
}