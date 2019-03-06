<?php
/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Class ClienteController
 */
class ClienteController {
    private $conectar, $conexion, $twig;

    /**
     * ClienteController constructor.
     */
    public function __construct() {
        require_once __DIR__ . "/../core/Conectar.php";
        require_once __DIR__ . "/../model/Cliente.php";

        $this->conectar = new Conectar();
        $this->conexion = $this->conectar->conexion();
    }

    /**
     * Función que dependiendo de la acción que reciba ejecuta la función correpondiente
     * @param string $accion
     */
    public function run($accion = "") {
        switch ($accion) {
            case "update":
                $this->update();
                break;
        }
    }

    /**
     * Función que actualiza los datos del cliente en la base de datos
     */
    private function update() {
        if(isset($_POST["idCliente"])) {
            $cliente=new Cliente($this->conexion);
            $cliente->setIdCliente($_POST["idCliente"]);
            $cliente->setNombre($_POST["nombre"]);
            $cliente->setApellidos($_POST["apellidos"]);
            $cliente->setEmpresa($_POST["empresa"]);
            $cliente->setEmail($_POST["email"]);
            $cliente->setTelefono($_POST["telefono"]);
            $cliente->update();
        }
    }
}