<?php

class Incidencia {
    private $conexion;
    private $idIncidencia, $descripcionBreve, $descripcionDetallada, $fecha, $prioridad, $estado, $categoria, $idCliente, $idEmpleado;

    /**
     * Incidencia constructor.
     * @param $conexion
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
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
    public function getDescripcionBreve() {
        return $this->descripcionBreve;
    }

    /**
     * @param mixed $descripcionBreve
     */
    public function setDescripcionBreve($descripcionBreve) {
        $this->descripcionBreve = $descripcionBreve;
    }

    /**
     * @return mixed
     */
    public function getDescripcionDetallada() {
        return $this->descripcionDetallada;
    }

    /**
     * @param mixed $descripcionDetallada
     */
    public function setDescripcionDetallada($descripcionDetallada) {
        $this->descripcionDetallada = $descripcionDetallada;
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
    public function getPrioridad() {
        return $this->prioridad;
    }

    /**
     * @param mixed $prioridad
     */
    public function setPrioridad($prioridad) {
        $this->prioridad = $prioridad;
    }

    /**
     * @return mixed
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getCategoria() {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    /**
     * @return mixed
     */
    public function getIdCliente() {
        return $this->idCliente;
    }

    /**
     * @param mixed $idCliente
     */
    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
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

    public function selectAllIncidencia() {
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Incidencia ORDER BY fecha DESC");
            $sentencia->execute();
            $resultado=array();
            while($fila=$sentencia->fetch())
            {
                $resultado[]=$fila;
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
            return null;
        }
    }

    public function insert() {
        $datos=array("descripcionBreve"=>$this->descripcionBreve,"descripcionDetallada"=>$this->descripcionDetallada, "prioridad"=>$this->prioridad,"estado"=>$this->estado,"categoria"=>$this->categoria,"idCliente"=>$this->idCliente,"idEmpleado"=>$this->idEmpleado);
        try {
            $sentencia=$this->conexion->prepare("INSERT INTO Incidencia (descripcionBreve, descripcionDetallada, fecha, prioridad, estado, categoria, idCliente, idEmpleado) 
            VALUES (:descripcionBreve, :descripcionDetallada, NOW(), :prioridad, :estado, :categoria, :idCliente, :idEmpleado)");
            $sentencia->execute($datos);
            $this->conexion=null;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }
}