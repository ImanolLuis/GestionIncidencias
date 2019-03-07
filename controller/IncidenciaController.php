<?php
/*!
 * Gestión de Incidencias v1.0.1
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

if(session_id()=='') {
    session_start();
}

/**
 * Class IncidenciaController
 */
class IncidenciaController {
    private $conectar, $conexion, $twig;

    /**
     * IncidenciaController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Incidencia.php";
        require_once __DIR__ . "/../model/Cliente.php";
        require_once __DIR__ . "/../model/Empleado.php";
        require_once __DIR__ . "/../model/Anotacion.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . "/../view/"));
    }

    /**
     * Función que dependiendo de la acción que reciba ejecuta la función correpondiente
     * @param string $accion
     */
    public function run($accion = "") {
        switch ($accion) {
            case "registrar":
                $this->registrar();
                break;
            case "ver":
                $this->ver();
                break;
            case "insert":
                $this->insert();
                break;
            case "cerrar":
                $this->cerrar();
                break;
            case "search":
                $this->search();
                break;
            case "updateTecnico":
                $this->updateTecnico();
                break;
            case "estadisticas":
                $this->estadisticas();
                break;
            case "getEstadisticas":
                $this->getEstadisticas();
                break;
            default:
                $this->inicio();
        }
    }

    /**
     * Función que muestra la pantalla de inicio con el listado de incidencias
     */
    private function inicio() {
        $incidencia=new Incidencia($this->conexion);
        $incidencias=$incidencia->selectAllIncidencia();

        $this->mostrarIncidencias($incidencias);
    }

    /**
     * Función que muestra la pantalla de registrar incidencias
     */
    private function registrar() {
        $cliente=new Cliente($this->conexion);
        $clientes=$cliente->selectAllCliente();
        $empleado=new Empleado($this->conexion);
        $empleados=$empleado->selectAllTecnico();

        echo $this->twig->render('RegistrarIncidenciaView.twig', array("clientes"=>$clientes,"empleados"=>$empleados));
    }

    /**
     * Función que muestra la pantalla del seguimiento de la incidencia
     */
    private function ver() {
        if(isset($_GET["incidencia"]))
        {
            $incidencia=new Incidencia($this->conexion);
            $incidencia->setIdIncidencia($_GET["incidencia"]);
            $incidencia=$incidencia->selectIncidenciaById();

            $cliente=new Cliente($this->conexion);
            $cliente->setIdCliente($incidencia["idCliente"]);
            $cliente=$cliente->selectClienteById();
            $empleado=new Empleado($this->conexion);
            $empleados=$empleado->selectAllTecnico();

            echo $this->twig->render('SeguimientoIncidenciaView.twig', array("incidencia"=>$incidencia,"cliente"=>$cliente,"empleados"=>$empleados, "usuario"=>$_SESSION["login"]));
        }
    }

    /**
     * Función que inserta una incidencia en la base de datos
     */
    private function insert() {
        if(isset($_POST["descripcionBreve"])) {
            $incidencia=new Incidencia($this->conexion);
            $incidencia->setDescripcionBreve($_POST["descripcionBreve"]);
            $incidencia->setDescripcionDetallada($_POST["descripcionDetallada"]);
            $incidencia->setPrioridad($_POST["prioridad"]);
            $incidencia->setEstado("Abierta");
            $incidencia->setCategoria($_POST["categoria"]);
            $incidencia->setIdCliente($_POST["cliente"]);
            if($_POST["tecnico"]=="")
            {
                $incidencia->setIdEmpleado(null);
            }
            else
            {
                $incidencia->setIdEmpleado($_POST["tecnico"]);
            }

            $incidencia->insert();

            header("Location: index.php");
        }
    }

    /**
     * Función que busca las incidencias filtradas por lo que quiera el usuario y muestra la pantalla de inicio con el listado de incidencias
     */
    private function search() {
        if(isset($_POST["prioridad"])) {
            $incidencia=new Incidencia($this->conexion);
            $incidencias=$incidencia->selectIncidenciaBySearch($this->validarDato("prioridad"),$this->validarDato("estado"),$this->validarDato("categoria"),$this->validarDato("tecnico"),$_SESSION["login"]["idEmpleado"],$this->validarDato("fecha"));
            $this->mostrarIncidencias($incidencias);
        }
    }

    /**
     * Función que cambia el estado de una incidencia a "Cerrada" en la base de datos
     */
    private function cerrar() {
        if(isset($_POST["idIncidencia"])) {
            $incidencia=new Incidencia($this->conexion);
            $incidencia->setIdIncidencia($_POST["idIncidencia"]);
            $incidencia->setEstado("Cerrada");
            $incidencia->closeIncidencia();
        }
    }

    /**
     * Función que cambia el técnico asignado de una incidencia en la base de datos
     */
    private function updateTecnico() {
        if(isset($_POST["idIncidencia"])) {
            $incidencia=new Incidencia($this->conexion);
            $incidencia->setIdIncidencia($_POST["idIncidencia"]);
            if($_POST["tecnico"]=="")
            {
                $incidencia->setIdEmpleado(null);
            }
            else
            {
                $incidencia->setIdEmpleado($_POST["tecnico"]);
            }

            $incidencia->updateTecnico();
        }
    }

    /**
     * Función que muestra la pantalla de las estadísticas
     */
    private function estadisticas() {

        echo $this->twig->render('EstadisticasView.twig');
    }

    /**
     * Función que obtiene los datos para mostralos en las estadísticas
     */
    private function getEstadisticas() {
        if(isset($_GET["tipo"])) {
            $incidencia = new Incidencia($this->conexion);
            switch ($_GET["tipo"]) {
                case "cliente":
                    $incidencias = $incidencia->selectIncidenciaByClienteStat($this->validarDato("prioridad"),$this->validarDato("estado"),$this->validarDato("fecha"));
                    break;
                case "empleado":
                    $incidencias = $incidencia->selectIncidenciaByEmpleadoStat($this->validarDato("prioridad"),$this->validarDato("estado"),$this->validarDato("fecha"));
                    break;
                default:
                    $incidencias = $incidencia->selectIncidenciaByCategoriaStat($this->validarDato("prioridad"),$this->validarDato("estado"),$this->validarDato("fecha"));
                    break;
            }
            header('Content-type: application/json');
            echo json_encode($incidencias);
        }
    }

    /**
     * Función que muestra las incidencias en pantalla tras obtener el nombre y los apellidos de los clientes y empleados
     * @param $incidencias
     */
    private function mostrarIncidencias($incidencias) {
        for($i=0;$i<count($incidencias);$i++) {
            $cliente=new Cliente($this->conexion);
            $incidencias[$i]["cliente"]=$cliente->selectNombreApellidosCliente($incidencias[$i]["idCliente"]);
            $empleado=new Empleado($this->conexion);
            $incidencias[$i]["empleado"]=$empleado->selectNombreApellidosEmpleado($incidencias[$i]["idEmpleado"]);
        }

        echo $this->twig->render('indexView.twig', array("incidencias"=>$incidencias));
    }

    /**
     * Función para validar que se ha enviado por POST un dato
     * @param $dato
     * @return string
     */
    private function validarDato($dato){
        if(isset($_POST[$dato])) {
            return $_POST[$dato];
        } else {
            return "";
        }
    }
}