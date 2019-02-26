<?php

class Empleado {
    private $conexion;
    private $idEmpleado, $nombre, $apellidos, $usuario, $contrasenna, $esTecnico;

    /**
     * Empleado constructor.
     * @param $conexion
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
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
     * @return mixed
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getApellidos() {
        return $this->apellidos;
    }

    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos) {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getContrasenna() {
        return $this->contrasenna;
    }

    /**
     * @param mixed $contrasenna
     */
    public function setContrasenna($contrasenna) {
        $this->contrasenna = $contrasenna;
    }

    /**
     * @return mixed
     */
    public function getEsTecnico() {
        return $this->esTecnico;
    }

    /**
     * @param mixed $esTecnico
     */
    public function setEsTecnico($esTecnico) {
        $this->esTecnico = $esTecnico;
    }

    public function validarUsuario() {
        $datos=array("usuario"=>$this->usuario, "contrasenna"=>$this->contrasenna);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Empleado WHERE usuario=:usuario AND contrasenna=:contrasenna");
            $sentencia->execute($datos);
            $resultado=$sentencia->fetch();
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
            return null;
        }
    }

    public function selectNombreApellidosEmpleado($idEmpleado) {
        $datos=array("idEmpleado"=>$idEmpleado);
        try {
            $sentencia=$this->conexion->prepare("SELECT nombre, apellidos FROM Empleado WHERE idEmpleado=:idEmpleado");
            $sentencia->execute($datos);
            $resultado="";
            while($fila=$sentencia->fetch())
            {
                $resultado=$fila["nombre"]." ".$fila["apellidos"];
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
            return null;
        }
    }

    public function selectAllTecnico() {
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Empleado WHERE esTecnico = 1");
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
}