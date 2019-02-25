<?php

class Anotacion
{
    private $conexion;

    private $idAnotacion, $fecha, $idIncidencia, $idEmpleado;

    /**
     * Anotacion constructor.
     * @param $conexion
     */
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * @return mixed
     */
    public function getIdAnotacion()
    {
        return $this->idAnotacion;
    }

    /**
     * @param mixed $idAnotacion
     */
    public function setIdAnotacion($idAnotacion)
    {
        $this->idAnotacion = $idAnotacion;
    }

    /**
     * @return mixed
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getIdIncidencia()
    {
        return $this->idIncidencia;
    }

    /**
     * @param mixed $idIncidencia
     */
    public function setIdIncidencia($idIncidencia)
    {
        $this->idIncidencia = $idIncidencia;
    }

    /**
     * @return mixed
     */
    public function getIdEmpleado()
    {
        return $this->idEmpleado;
    }

    /**
     * @param mixed $idEmpleado
     */
    public function setIdEmpleado($idEmpleado)
    {
        $this->idEmpleado = $idEmpleado;
    }
}