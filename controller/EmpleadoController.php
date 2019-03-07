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
 * Class EmpleadoController
 */
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

    /**
     * Función que dependiendo de la acción que reciba ejecuta la función correpondiente.
     * @param string $accion
     */
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

    /**
     * Función que muestra la pantalla de inicio de sesión.
     */
    private function inicioSesiom() {
        echo $this->twig->render("loginView.twig");
    }

    /**
     * Función que valida al usuario al iniciar sesión.
     * Si el usuario es correcto, muestra la página de inicio.
     * Si el usuario es  incorrecto, vuelve a mostrar la página de inicio de sesión.
     */
    private function login() {
        if(isset($_POST["usuario"])) {
            $usuario=$_POST["usuario"];
            $contrasenna=$_POST["contrasenna"];

            $empleado=new Empleado($this->conexion);
            $empleado->setUsuario($usuario);
            $empleado->setContrasenna($contrasenna);
            $empleado=$empleado->validarUsuario();

            if($empleado) {
                $_SESSION["login"]=$empleado;
                header("Location: index.php");
            } else {
                echo $this->twig->render('loginView.twig', array("error"=>true,"usuario"=>$usuario, "contrasenna"=>$contrasenna));
            }
        } else {
            header("Location: index.php");
        }
    }

    /**
     * Función que cierra sesión.
     */
    private function logout() {
        session_destroy();
        header("Location: index.php");
    }
}