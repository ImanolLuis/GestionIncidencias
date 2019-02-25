<?php

class IncidenciaController
{
    private $conectar;
    private $conexion;
    private $twig;

    /**
     * IncidenciaController constructor.
     */
    public function __construct()
    {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Incidencia.php";
        require_once __DIR__ . "/../vendor/autoload.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
        $this->twig = new Twig_Environment(new Twig_Loader_Filesystem("view"));
    }

    public function run($accion = "")
    {
        switch ($accion)
        {
            default:
        }
    }
}