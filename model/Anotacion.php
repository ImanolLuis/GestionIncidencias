<?php
/*!
 * Gestión de Incidencias v1.0.1
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Class Anotacion
 */
class Anotacion {
    private $conexion;
    private $idAnotacion, $anotacion, $fecha, $idIncidencia, $idEmpleado;

    /**
     * Anotacion constructor.
     * @param $conexion
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /**
     * Getters y setters de la clase Anotación
     */

    /**
     * @return mixed
     */
    public function getIdAnotacion() {
        return $this->idAnotacion;
    }

    /**
     * @param mixed $idAnotacion
     */
    public function setIdAnotacion($idAnotacion) {
        $this->idAnotacion = $idAnotacion;
    }

    /**
     * @return mixed
     */
    public function getAnotacion()
    {
        return $this->anotacion;
    }

    /**
     * @param mixed $anotacion
     */
    public function setAnotacion($anotacion)
    {
        $this->anotacion = $anotacion;
    }

    /**
     * @return mixed
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * @param mixed $fecha
     */
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    /**
     * @return mixed
     */
    public function getIdIncidencia() {
        return $this->idIncidencia;
    }

    /**
     * @param mixed $idIncidencia
     */
    public function setIdIncidencia($idIncidencia) {
        $this->idIncidencia = $idIncidencia;
    }

    /**
     * @return mixed
     */
    public function getIdEmpleado() {
        return $this->idEmpleado;
    }

    /**
     * @param mixed $idEmpleado
     */
    public function setIdEmpleado($idEmpleado) {
        $this->idEmpleado = $idEmpleado;
    }

    /**
     * Selecciona todas las anotaciones de la base de datos por el idIncidencia
     * @return array|null
     */
    public function selectAllAnotacionByIncidencia() {
        $datos=array("idIncidencia"=>$this->idIncidencia);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Anotacion WHERE idIncidencia=:idIncidencia ORDER BY fecha DESC");
            $sentencia->execute($datos);
            $resultado=array();
            while($fila=$sentencia->fetch()) {
                $resultado[]=$fila;
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
            return null;
        }
    }

    /**
     * Inserta una anotación en la base de datos
     */
    public function insert() {
        $datos=array("anotacion"=>$this->anotacion,"idIncidencia"=>$this->idIncidencia, "idEmpleado"=>$this->idEmpleado);
        try {
            $sentencia=$this->conexion->prepare("INSERT INTO Anotacion (anotacion, fecha, idIncidencia, idEmpleado) 
            VALUES (:anotacion, NOW(), :idIncidencia, :idEmpleado)");
            $sentencia->execute($datos);
            $this->conexion=null;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }
}