<?php

if(session_id()=='') {
    session_start();
}

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

    private function inicio() {
        $incidencia=new Incidencia($this->conexion);
        $incidencias=$incidencia->selectAllIncidencia();

        $this->mostrarIncidencias($incidencias);
    }

    private function registrar() {
        $cliente=new Cliente($this->conexion);
        $clientes=$cliente->selectAllCliente();
        $empleado=new Empleado($this->conexion);
        $empleados=$empleado->selectAllTecnico();

        echo $this->twig->render('RegistrarIncidenciaView.twig', array("clientes"=>$clientes,"empleados"=>$empleados));
    }

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

    private function insert() {
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

    private function search() {
        if(isset($_POST["tipo"]))
        {
            $incidencia=new Incidencia($this->conexion);
            switch($_POST["tipo"]) {
                case "prioridad":
                    $incidencias=$incidencia->selectIncidenciaByPrioridad($_POST["prioridad"]);
                    break;
                case "estado":
                    $incidencias=$incidencia->selectIncidenciaByEstado($_POST["estado"]);
                    break;
                case "categoria":
                    $incidencias=$incidencia->selectIncidenciaByCategoria($_POST["categoria"]);
                    break;
                case "tecnico":
                    if($_POST["tecnico"]=="Yo") {
                        $incidencias=$incidencia->selectIncidenciaByEmpleado($_SESSION["login"]["idEmpleado"], "Yo");
                    } else {
                        $incidencias=$incidencia->selectIncidenciaByEmpleado("", "SinAsignar");
                    }
                    break;
                default:
                    switch($_POST["fecha"]) {
                        case "ultimaSemana":
                            $incidencias=$incidencia->selectIncidenciaByFecha("ultimaSemana");
                            break;
                        case "ultimoMes":
                            $incidencias=$incidencia->selectIncidenciaByFecha("ultimoMes");
                            break;
                        default:
                            $incidencias=$incidencia->selectIncidenciaByFecha("");
                            break;
                    }
                    break;
            }
            $this->mostrarIncidencias($incidencias);
        }
    }

    private function cerrar() {
        if(isset($_POST["idIncidencia"])) {
            $incidencia=new Incidencia($this->conexion);
            $incidencia->setIdIncidencia($_POST["idIncidencia"]);
            $incidencia->setEstado("Cerrada");
            $incidencia->closeIncidencia();
        }
    }

    private function updateTecnico() {
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

    private function estadisticas() {

        echo $this->twig->render('EstadisticasView.twig');
    }

    private function getEstadisticas() {
        if(isset($_GET["tipo"])) {
            $incidencia = new Incidencia($this->conexion);
            switch ($_GET["tipo"]) {
                case "cliente":
                    if(isset($_POST["prioridad"])) {
                        $incidencias = $incidencia->selectIncidenciaByClienteStat("prioridad", $_POST["prioridad"]);
                    } elseif(isset($_POST["estado"])) {
                        $incidencias = $incidencia->selectIncidenciaByClienteStat("estado", $_POST["estado"]);
                    } elseif(isset($_POST["fecha"])) {
                        $incidencias = $incidencia->selectIncidenciaByClienteStat("fecha", $_POST["fecha"]);
                    } else {
                        $incidencias = $incidencia->selectIncidenciaByClienteStat("", "");
                    }
                    break;
                case "empleado":
                    if(isset($_POST["prioridad"])) {
                        $incidencias = $incidencia->selectIncidenciaByEmpleadoStat("prioridad", $_POST["prioridad"]);
                    } elseif(isset($_POST["estado"])) {
                        $incidencias = $incidencia->selectIncidenciaByEmpleadoStat("estado", $_POST["estado"]);
                    } elseif(isset($_POST["fecha"])) {
                        $incidencias = $incidencia->selectIncidenciaByEmpleadoStat("fecha", $_POST["fecha"]);
                    } else {
                        $incidencias = $incidencia->selectIncidenciaByEmpleadoStat("", "");
                    }
                    break;
                default:
                    if(isset($_POST["prioridad"])) {
                        $incidencias = $incidencia->selectIncidenciaByCategoriaStat("prioridad", $_POST["prioridad"]);
                    } elseif(isset($_POST["estado"])) {
                        $incidencias = $incidencia->selectIncidenciaByCategoriaStat("estado", $_POST["estado"]);
                    } elseif(isset($_POST["fecha"])) {
                        $incidencias = $incidencia->selectIncidenciaByCategoriaStat("fecha", $_POST["fecha"]);
                    } else {
                        $incidencias = $incidencia->selectIncidenciaByCategoriaStat("", "");
                    }
                    break;
            }
            header('Content-type: application/json');
            echo json_encode($incidencias);
        }
    }

    private function mostrarIncidencias($incidencias) {
        for($i=0;$i<count($incidencias);$i++)
        {
            $cliente=new Cliente($this->conexion);
            $incidencias[$i]["cliente"]=$cliente->selectNombreApellidosCliente($incidencias[$i]["idCliente"]);
            $empleado=new Empleado($this->conexion);
            $incidencias[$i]["empleado"]=$empleado->selectNombreApellidosEmpleado($incidencias[$i]["idEmpleado"]);
        }

        echo $this->twig->render('indexView.twig', array("incidencias"=>$incidencias));
    }
}