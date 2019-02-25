<?php

if(session_id()=='') {
    session_start();
}

class EmpleadoController {
    private $conectar, $conexion, $twig;

    /**
     * EmpleadoController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Empleado.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . "/../view/"));
    }

    public function run($accion = "") {
        switch ($accion) {
            case "login":
                $this->login();
                break;
            case "logout":
                $this->logout();
                break;
            default:
                $this->inicioSesiom();
        }
    }

    private function inicioSesiom() {
        echo $this->twig->render("loginView.twig");
    }

    private function login() {
        if(isset($_POST["usuario"])) {
            $usuario=$_POST["usuario"];
            $contrasenna=$_POST["contrasenna"];

            $empleado=new Empleado($this->conexion);
            $empleado->setUsuario($usuario);
            $empleado->setContrasenna($contrasenna);
            $empleado=$empleado->validarUsuario();

            if($empleado) {
                $_SESSION["login"]=true;
                header("Location: index.php");
            } else {
                echo $this->twig->render('loginView.twig', array("error"=>true,"usuario"=>$usuario, "contrasenna"=>$contrasenna));
            }
        } else {
            header("Location: index.php");
        }
    }

    private function logout() {
        session_destroy();
        header("Location: index.php");
    }
}