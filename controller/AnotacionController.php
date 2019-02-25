<?php

class AnotacionController {
    private $conectar, $conexion, $twig;

    /**
     * AnotacionController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Anotacion.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem(__DIR__ . "/../view/"));
    }

    public function run($accion = "") {
        switch ($accion) {
            default:
        }
    }
}