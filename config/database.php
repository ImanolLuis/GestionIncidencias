<?php
/*!
 * Gestión de Incidencias v1.0
 * Copyright 2019 Imanol Luis
 * Licensed under MIT (https://github.com/ImanolLuis/GestionIncidencias/blob/master/LICENSE)
 */

/**
 * Si el sistema gestor de la base de datos es Microsoft SQL Server, cambie el valor de "DB_DRIVER" a "mssql"
 * Si el sistema gestor de la base de datos es MySQL, cambie el valor de "DB_DRIVER" a "mysql"
 * Si el sistema gestor de la base de datos es SQLite, cambie el valor de "DB_DRIVER" a "sqlite"
 */
define("DB_DRIVER", "mysql");

/**
 * Introduxca la dirección del servidor donde se encuentra alojada la base de datos.
 */
define("DB_HOST", "localhost");

/**
 * Introduzca el usuario de la base de datos para interactuar con la aplicación
 */
define("DB_USER", "root");

/**
 * Introduzca la contraseña del usuario de la base de datos
 */
define("DB_PASS", "");

/**
 * Introduzca el nombre de la base de datos
 */
define("DB_DATABASE", "GestionIncidencias");

/**
 * Introduzca el cotejamiento de valores de la base de datos
 * Valor predeterminado: "utf8"
 */
define("DB_CHARSET", "utf8");

/**
 * Sólo para SQLite
 * Introduzca la ruta del script de base de datos
 */
define("DB_PATH", "my/database/path/database.db");
?>