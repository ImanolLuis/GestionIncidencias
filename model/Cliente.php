<?php

class Cliente {
    private $conexion;
    private $idCliente, $nombre, $apellidos, $empresa, $email, $telefono;

    /**
     * Cliente constructor.
     * @param $conexion
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
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
    public function getEmpresa() {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function selectClienteById() {
        $datos=array("idCliente"=>$this->idCliente);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Cliente WHERE idCliente=:idCliente");
            $sentencia->execute($datos);
            $resultado=$sentencia->fetch();
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
            return null;
        }
    }

    public function selectNombreApellidosCliente($idCliente) {
        $datos=array("idCliente"=>$idCliente);
        try {
            $sentencia=$this->conexion->prepare("SELECT nombre, apellidos FROM Cliente WHERE idCliente=:idCliente");
            $sentencia->execute($datos);
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

    public function selectAllCliente() {
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Cliente");
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

    public function update() {
        $datos=array("idCliente"=>$this->idCliente,"nombre"=>$this->nombre, "apellidos"=>$this->apellidos,"empresa"=>$this->empresa,"email"=>$this->email,"telefono"=>$this->telefono);
        try {
            $sentencia=$this->conexion->prepare("UPDATE Cliente SET nombre = :nombre, apellidos = :apellidos, empresa = :empresa, email = :email, telefono = :telefono WHERE idCliente = :idCliente");
            $sentencia->execute($datos);
            $this->conexion=null;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }
}