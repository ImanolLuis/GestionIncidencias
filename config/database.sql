SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema GestionIncidencias
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `GestionIncidencias` ;

-- -----------------------------------------------------
-- Schema GestionIncidencias
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `GestionIncidencias` DEFAULT CHARACTER SET utf8 ;
USE `GestionIncidencias` ;

-- -----------------------------------------------------
-- Table `GestionIncidencias`.`empleado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `GestionIncidencias`.`empleado` ;

CREATE TABLE IF NOT EXISTS `GestionIncidencias`.`empleado` (
  `idEmpleado` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NULL,
  `apellidos` VARCHAR(200) NULL,
  `usuario` VARCHAR(100) NULL,
  `contrasenna` VARCHAR(100) NULL,
  `esTecnico` TINYINT NULL,
  PRIMARY KEY (`idEmpleado`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestionIncidencias`.`cliente`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `GestionIncidencias`.`cliente` ;

CREATE TABLE IF NOT EXISTS `GestionIncidencias`.`cliente` (
  `idCliente` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NULL,
  `apellidos` VARCHAR(200) NULL,
  `empresa` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `telefono` VARCHAR(15) NULL,
  PRIMARY KEY (`idCliente`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestionIncidencias`.`incidencia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `GestionIncidencias`.`incidencia` ;

CREATE TABLE IF NOT EXISTS `GestionIncidencias`.`incidencia` (
  `idIncidencia` INT NOT NULL AUTO_INCREMENT,
  `descripcionBreve` VARCHAR(100) NULL,
  `descripcionDetallada` VARCHAR(255) NULL,
  `fecha` DATETIME NULL,
  `prioridad` VARCHAR(100) NULL,
  `estado` VARCHAR(100) NULL,
  `categoria` VARCHAR(100) NULL,
  `idCliente` INT NOT NULL,
  `idEmpleado` INT NULL,
  PRIMARY KEY (`idIncidencia`),
  INDEX `fk_incidencia_cliente_idx` (`idCliente` ASC),
  INDEX `fk_incidencia_empleado_idx` (`idEmpleado` ASC),
  CONSTRAINT `fk_incidencia_cliente`
    FOREIGN KEY (`idCliente`)
    REFERENCES `GestionIncidencias`.`cliente` (`idCliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_incidencia_empleado`
    FOREIGN KEY (`idEmpleado`)
    REFERENCES `GestionIncidencias`.`empleado` (`idEmpleado`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `GestionIncidencias`.`anotacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `GestionIncidencias`.`anotacion` ;

CREATE TABLE IF NOT EXISTS `GestionIncidencias`.`anotacion` (
  `idAnotacion` INT NOT NULL AUTO_INCREMENT,
  `anotacion` VARCHAR(255) NULL,
  `fecha` DATETIME NULL,
  `idIncidencia` INT NOT NULL,
  `idEmpleado` INT NOT NULL,
  PRIMARY KEY (`idAnotacion`),
  INDEX `fk_anotacion_incidencia_idx` (`idIncidencia` ASC),
  INDEX `fk_anotacion_empleado_idx` (`idEmpleado` ASC),
  CONSTRAINT `fk_anotacion_incidencia`
    FOREIGN KEY (`idIncidencia`)
    REFERENCES `GestionIncidencias`.`incidencia` (`idIncidencia`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_anotacion_empleado`
    FOREIGN KEY (`idEmpleado`)
    REFERENCES `GestionIncidencias`.`empleado` (`idEmpleado`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
