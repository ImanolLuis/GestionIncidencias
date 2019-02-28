<?php

class AnotacionController {
    private $conectar, $conexion, $twig;

    /**
     * AnotacionController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Anotacion.php";
        require_once __DIR__ . "/../model/Empleado.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . "/../view/"));
    }

    public function run($accion = "") {
        switch ($accion) {
            case "insert":
                $this->insert();
                break;
            default:
                $this->selectAll();
        }
    }

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