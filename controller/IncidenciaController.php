<?php

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
            case "insert":
                $this->insert();
                break;
            default:
                $this->inicio();
        }
    }

    private function inicio() {
        $incidencia=new Incidencia($this->conexion);
        $incidencias=$incidencia->selectAllIncidencia();

        for($i=0;$i<count($incidencias);$i++)
        {
            $cliente=new Cliente($this->conexion);
            $incidencias[$i]["cliente"]=$cliente->selectNombreApellidosCliente($incidencias[$i]["idCliente"]);
            $empleado=new Empleado($this->conexion);
            $incidencias[$i]["empleado"]=$empleado->selectNombreApellidosEmpleado($incidencias[$i]["idEmpleado"]);
        }

        echo $this->twig->render('indexView.twig', array("incidencias"=>$incidencias));
    }

    private function registrar() {
        $cliente=new Cliente($this->conexion);
        $clientes=$cliente->selectAllCliente();
        $empleado=new Empleado($this->conexion);
        $empleados=$empleado->selectAllTecnico();


        echo $this->twig->render('RegistrarIncidenciaView.twig', array("clientes"=>$clientes,"empleados"=>$empleados));
    }

    private function insert() {
        $incidencia=new Incidencia($this->conexion);
        $incidencia->setDescripcionBreve($_POST["descripcionBreve"]);
        $incidencia->setDescripcionDetallada($_POST["descripcionDetallada"]);
        $incidencia->setPrioridad($_POST["prioridad"]);
        $incidencia->setEstado($_POST["estado"]);
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