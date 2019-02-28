<?php

class ClienteController {
    private $conectar, $conexion, $twig;

    /**
     * ClienteController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Cliente.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . "/../view/"));
    }

    public function run($accion = "") {
        switch ($accion) {
            case "update":
                $this->update();
                break;
            default:
                $this->update();
        }
    }

    private function update() {
        if(isset($_POST["idCliente"])) {
            $cliente=new Cliente($this->conexion);
            $cliente->setIdCliente($_POST["idCliente"]);
            $cliente->setNombre($_POST["nombre"]);
            $cliente->setApellidos($_POST["apellidos"]);
            $cliente->setEmpresa($_POST["empresa"]);
            $cliente->setEmail($_POST["email"]);
            $cliente->setTelefono($_POST["telefono"]);
            $cliente->update();
        }
    }
}