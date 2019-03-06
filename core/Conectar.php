<?php
/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Class Conectar
 */
class Conectar
{
    private $driver;
    private $host, $user, $pass, $database, $charset, $path;

    /**
     * Conectar constructor.
     */
    public function __construct() {
        require_once 'config/database.php';
        $this->driver=DB_DRIVER;
        $this->host=DB_HOST;
        $this->user=DB_USER;
        $this->pass=DB_PASS;
        $this->database=DB_DATABASE;
        $this->charset=DB_CHARSET;
        $this->path=DB_PATH;
    }

    /**
     * Función que establece conexión con la base de datos.
     * @return PDO
     */
    public function conexion(){

        $bbdd = $this->driver .':host='. $this->host .  ';dbname=' . $this->database . ';charset=' . $this->charset;

        try {
            if($this->driver=="sqlite") {
                $connection = new PDO($this->driver . ':' . $this->path);
            } else {
                $connection = new PDO($bbdd, $this->user, $this->pass);
            }
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $connection;
        } catch (PDOException $e) {
            die('Problema al establecer conexión.');
        }
    }
}