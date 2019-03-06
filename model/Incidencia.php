<?php
/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Class Incidencia
 */
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
     * Getters y setters de la clase Incidencia
     */

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

    /**
     * Selecciona todas las incidencias de la base de datos
     * @return array|null
     */
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

    /**
     * Selecciona una incidencia de la base de datos por la idIncidencia
     * @return null
     */
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

    /**
     * Inserta una incidencia en la base de datos
     */
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

    /**
     * Selecciona todas las incidencias de la base de datos con filtros
     * @param string $prioridad
     * @param string $estado
     * @param string $categoria
     * @param string $empleado
     * @param string $idEmpleado
     * @param string $fecha
     * @return array|null
     */
    public function selectIncidenciaBySearch($prioridad = "", $estado = "", $categoria = "", $empleado = "", $idEmpleado = "", $fecha = "") {

        $condicion=$this->obtenerCondicion($prioridad, $estado, $categoria, $empleado, $fecha);
        $consulta="SELECT * FROM Incidencia" . $condicion . " ORDER BY fecha DESC";
        if($prioridad!="") {
            if($estado!="") {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        $datos=array("prioridad"=>$prioridad, "estado"=>$estado, "categoria"=>$categoria, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("prioridad"=>$prioridad, "estado"=>$estado, "categoria"=>$categoria);
                    }
                } else {
                    if($empleado=="Yo") {
                        $datos=array("prioridad"=>$prioridad, "estado"=>$estado, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("prioridad"=>$prioridad, "estado"=>$estado);
                    }
                }
            } else {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        $datos=array("prioridad"=>$prioridad, "categoria"=>$categoria, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("prioridad"=>$prioridad, "categoria"=>$categoria);
                    }
                } else {
                    if($empleado=="Yo") {
                        $datos=array("prioridad"=>$prioridad, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("prioridad"=>$prioridad);
                    }
                }
            }
        } else {
            if($estado!="") {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        $datos=array("estado"=>$estado, "categoria"=>$categoria, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("estado"=>$estado, "categoria"=>$categoria);
                    }
                } else {
                    if($empleado=="Yo") {
                        $datos=array("estado"=>$estado, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("estado"=>$estado);
                    }
                }
            } else {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        $datos=array("categoria"=>$categoria, "idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array("categoria"=>$categoria);
                    }
                } else {
                    if($empleado=="Yo") {
                        $datos=array("idEmpleado"=>$idEmpleado);
                    } else {
                        $datos=array();
                    }
                }
            }
        }
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
            return null;
        }
    }

    /**
     * Actualiza el estado de la incidencia de la base da datos para marcar la incidencia como cerrada
     */
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

    /**
     * Actualiza el técnico asignado de la incidencia en la base de datos
     */
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

    /**
     * Selecciona el número de incidencias agrupados por categoria y filtrando por prioridad, estado, fecha o sin filtrar
     * Para las estadísticas
     * @param string $prioridad
     * @param string $estado
     * @param string $fecha
     * @return array|null
     */
    public function selectIncidenciaByCategoriaStat($prioridad = "", $estado = "", $fecha = "") {

        $condicion=$this->obtenerCondicionStat($prioridad, $estado, $fecha);
        $consulta="SELECT categoria AS etiqueta, COUNT(*) AS dato FROM Incidencia" . $condicion . " GROUP BY categoria ORDER BY COUNT(*) DESC";
        if($prioridad!="") {
            if($estado!="") {
                $datos=array("prioridad"=>$prioridad, "estado"=>$estado);
            } else {
                $datos=array("prioridad"=>$prioridad);
            }
        } else {
            if($estado!="") {
                $datos=array("estado"=>$estado);
            } else {
                $datos=array();
            }
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

    /**
     * Selecciona el número de incidencias agrupados por cliente y filtrando por prioridad, estado, fecha o sin filtrar
     * Para las estadísticas
     * @param string $prioridad
     * @param string $estado
     * @param string $fecha
     * @return array|null
     */
    public function selectIncidenciaByClienteStat($prioridad = "", $estado = "", $fecha = "") {

        $condicion=$this->obtenerCondicionStat($prioridad, $estado, $fecha);
        $consulta="SELECT CONCAT(Cliente.nombre, ' ', Cliente.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia 
INNER JOIN Cliente ON Cliente.idCliente = Incidencia.idCliente" . $condicion . " GROUP BY Incidencia.idCliente ORDER BY COUNT(*) DESC";
        if($prioridad!="") {
            if($estado!="") {
                $datos=array("prioridad"=>$prioridad, "estado"=>$estado);
            } else {
                $datos=array("prioridad"=>$prioridad);
            }
        } else {
            if($estado!="") {
                $datos=array("estado"=>$estado);
            } else {
                $datos=array();
            }
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

    /**
     * Selecciona el número de incidencias agrupados por empleado y filtrando por prioridad, estado, fecha o sin filtrar
     * Para las estadísticas
     * @param string $prioridad
     * @param string $estado
     * @param string $fecha
     * @return array|null
     */
    public function selectIncidenciaByEmpleadoStat($prioridad = "", $estado = "", $fecha = "") {

        $condicion=$this->obtenerCondicionStat($prioridad, $estado, $fecha);
        $consulta="SELECT CONCAT(Empleado.nombre, ' ', Empleado.apellidos) AS etiqueta, COUNT(*) AS dato FROM Incidencia
INNER JOIN Empleado ON Empleado.idEmpleado = Incidencia.idEmpleado" . $condicion . " GROUP BY Incidencia.idEmpleado ORDER BY COUNT(*) DESC";
        if($prioridad!="") {
            if($estado!="") {
                $datos=array("prioridad"=>$prioridad, "estado"=>$estado);
            } else {
                $datos=array("prioridad"=>$prioridad);
            }
        } else {
            if($estado!="") {
                $datos=array("estado"=>$estado);
            } else {
                $datos=array();
            }
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

    /**
     * Obtener condición para buscar con filtros
     * @param $prioridad
     * @param $estado
     * @param $categoria
     * @param $empleado
     * @param $fecha
     * @return string
     */
    private function obtenerCondicion($prioridad, $estado, $categoria, $empleado, $fecha) {
        if($prioridad!="") {
            if($estado!="") {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND categoria = :categoria";
                        }
                    }
                } else {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND estado = :estado";
                        }
                    }
                }
            } else {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND categoria = :categoria";
                        }
                    }
                } else {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE prioridad = :prioridad";
                        }
                    }
                }
            }
        } else {
            if($estado!="") {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado AND categoria = :categoria";
                        }
                    }
                } else {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE estado = :estado";
                        }
                    }
                }
            } else {
                if($categoria!="") {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE categoria = :categoria AND idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE categoria = :categoria AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE categoria = :categoria";
                        }
                    }
                } else {
                    if($empleado=="Yo") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE idEmpleado = :idEmpleado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE idEmpleado = :idEmpleado";
                        }
                    } elseif($empleado=="SinAsignar") {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE idEmpleado IS NULL AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion=" WHERE idEmpleado IS NULL";
                        }
                    } else {
                        if($fecha=="ultimaSemana") {
                            $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        } elseif ($fecha=="ultimoMes") {
                            $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                        } elseif ($fecha=="ultimoAnno") {
                            $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                        } else {
                            $condicion="";
                        }
                    }
                }
            }
        }
        return $condicion;
    }

    /**
     * Obtener condición para buscar por filtros
     * Página de estadísticas
     * @param $prioridad
     * @param $estado
     * @param $fecha
     * @return string
     */
    private function obtenerCondicionStat($prioridad, $estado, $fecha) {
        if($prioridad!="") {
            if($estado!="") {
                if($fecha=="ultimaSemana") {
                    $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                } elseif ($fecha=="ultimoMes") {
                    $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                } elseif ($fecha=="ultimoAnno") {
                    $condicion=" WHERE prioridad = :prioridad AND estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                } else {
                    $condicion=" WHERE prioridad = :prioridad AND estado = :estado";
                }
            } else {
                if($fecha=="ultimaSemana") {
                    $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                } elseif ($fecha=="ultimoMes") {
                    $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                } elseif ($fecha=="ultimoAnno") {
                    $condicion=" WHERE prioridad = :prioridad AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                } else {
                    $condicion=" WHERE prioridad = :prioridad";
                }
            }
        } else {
            if($estado!="") {
                if($fecha=="ultimaSemana") {
                    $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                } elseif ($fecha=="ultimoMes") {
                    $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                } elseif ($fecha=="ultimoAnno") {
                    $condicion=" WHERE estado = :estado AND fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                } else {
                    $condicion=" WHERE estado = :estado";
                }
            } else {
                if($fecha=="ultimaSemana") {
                    $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                } elseif ($fecha=="ultimoMes") {
                    $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                } elseif ($fecha=="ultimoAnno") {
                    $condicion=" WHERE fecha >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
                } else {
                    $condicion="";
                }
            }
        }
        return $condicion;
    }
}