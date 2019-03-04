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

    public function selectIncidenciaById() {
        $datos=array("idIncidencia"=>$this->idIncidencia);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Incidencia WHERE idIncidencia = :idIncidencia");
            $sentencia->execute($datos);
            $resultado=$sentencia->fetch();
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

    public function selectIncidenciaByPrioridad($prioridad) {
        $datos=array("prioridad"=>$prioridad);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Incidencia WHERE prioridad = :prioridad ORDER BY fecha DESC");
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

    public function selectIncidenciaByEstado($estado) {
        $datos=array("estado"=>$estado);
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Incidencia WHERE estado = :estado ORDER BY fecha DESC");
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

    public function selectIncidenciaByCategoria($categoria) {
        $datos=array("categoria"=>"%$categoria%");
        try {
            $sentencia=$this->conexion->prepare("SELECT * FROM Incidencia WHERE categoria LIKE :categoria ORDER BY fecha DESC");
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

    public function selectIncidenciaByEmpleado($empleado, $tipo) {
        $datos=array("idEmpleado"=>$empleado);
        if($tipo=="Yo") {
            $consulta="SELECT * FROM Incidencia WHERE idEmpleado = :idEmpleado ORDER BY fecha DESC";
        } else {
            $consulta="SELECT * FROM Incidencia WHERE idEmpleado IS NULL ORDER BY fecha DESC";
        }
        try {
            $sentencia=$this->conexion->prepare($consulta);
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

    public function selectIncidenciaByFecha($fecha) {
        switch($fecha) {
            case "ultimaSemana":
                $consulta="SELECT * FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY fecha DESC";
                break;
            case "ultimoMes":
                $consulta="SELECT * FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY fecha DESC";
                break;
            default:
                $consulta="SELECT * FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR) ORDER BY fecha DESC";
                break;
        }
        try {
            $sentencia=$this->conexion->prepare($consulta);
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

    public function closeIncidencia() {
        $datos=array("idIncidencia"=>$this->idIncidencia,"estado"=>$this->estado);
        try {
            $sentencia=$this->conexion->prepare("UPDATE Incidencia SET estado = :estado WHERE idIncidencia = :idIncidencia");
            $sentencia->execute($datos);
            $this->conexion=null;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }

    public function updateTecnico() {
        $datos=array("idIncidencia"=>$this->idIncidencia,"idEmpleado"=>$this->idEmpleado);
        try {
            $sentencia=$this->conexion->prepare("UPDATE Incidencia SET idEmpleado = :idEmpleado WHERE idIncidencia = :idIncidencia");
            $sentencia->execute($datos);
            $this->conexion=null;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }

    public function selectIncidenciaByCategoriaStat($tipo = "", $dato = "") {
        switch($tipo) {
            case "prioridad":
                $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia WHERE prioridad = :dato GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                break;
            case "estado":
                $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia WHERE estado = :dato GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                break;
            case "fecha":
                switch($dato) {
                    case "ultimaSemana":
                        $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                        break;
                    case "ultimoMes":
                        $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                        break;
                    default:
                        $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                        break;
                }
                break;
            default:
                $consulta="SELECT categoria AS etiqueta, COUNT(idIncidencia) AS dato FROM Incidencia GROUP BY categoria ORDER BY COUNT(idIncidencia) DESC";
                break;
        }
        $datos=array("dato"=>$dato);
        try {
            $sentencia=$this->conexion->prepare($consulta);
            $sentencia->execute($datos);
            $resultado=array();
            while($fila=$sentencia->fetch())
            {
                $resultado[]=$fila;
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }

    public function selectIncidenciaByClienteStat($tipo = "", $dato = "") {
        switch($tipo) {
            case "prioridad":
                $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato FROM Incidencia
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente WHERE Incidencia.prioridad = :dato GROUP BY Incidencia.idCliente ORDER BY COUNT(Incidencia.idIncidencia) DESC";
                break;
            case "estado":
                $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato FROM Incidencia
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente WHERE Incidencia.estado = :dato GROUP BY Incidencia.idCliente ORDER BY COUNT(Incidencia.idIncidencia) DESC";
                break;
            case "fecha":
                switch($dato) {
                    case "ultimaSemana":
                        $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato FROM Incidencia
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY Incidencia.idCliente ORDER BY COUNT(Incidencia.idIncidencia) DESC";
                        break;
                    case "ultimoMes":
                        $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato FROM Incidencia 
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY Incidencia.idCliente ORDER BY COUNT(Incidencia.idIncidencia) DESC";
                        break;
                    default:
                        $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato  FROM Incidencia
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY Incidencia.idCliente ORDER BY COUNT(Incidencia.idIncidencia) DESC";
                        break;
                }
                break;
            default:
                $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(Incidencia.idIncidencia) AS dato FROM Incidencia 
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente GROUP BY Incidencia.idCliente";
                break;
        }
        $datos=array("dato"=>$dato);
        try {
            $sentencia=$this->conexion->prepare($consulta);
            $sentencia->execute($datos);
            $resultado=array();
            while($fila=$sentencia->fetch())
            {
                $resultado[]=$fila;
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }

    public function selectIncidenciaByEmpleadoStat($tipo = "", $dato = "") {
        switch($tipo) {
            case "prioridad":
                $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia 
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado WHERE Incidencia.prioridad = :dato GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                break;
            case "estado":
                $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado WHERE Incidencia.estado = :dato GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                break;
            case "fecha":
                switch($dato) {
                    case "ultimaSemana":
                        $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                        break;
                    case "ultimoMes":
                        $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                        break;
                    default:
                        $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado WHERE Incidencia.fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                        break;
                }
                break;
            default:
                $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia 
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
                break;
        }
        $datos=array("dato"=>$dato);
        try {
            $sentencia=$this->conexion->prepare($consulta);
            $sentencia->execute($datos);
            $resultado=array();
            while($fila=$sentencia->fetch())
            {
                $resultado[]=$fila;
            }
            $this->conexion=null;
            return $resultado;
        } catch (PDOException $e) {
            $this->conexion=null;
        }
    }
}