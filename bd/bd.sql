DROP DATABASE IF EXISTS `timetracker`;
CREATE DATABASE IF NOT EXISTS `timetracker` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

use timetracker;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `timetracker`.`usuarios` (
    `idusuario` INT(5) NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(20) NOT NULL,
    `clave` VARCHAR(20) NOT NULL,
    `fecha_registro` DATE NOT NULL,
    `ultima_sesion` DATETIME NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `foto` VARCHAR(50) NULL DEFAULT 'default.svg',
     PRIMARY KEY (idusuario)
);

DROP TABLE IF EXISTS `actividades`;
CREATE TABLE `timetracker`.`actividades` (
    `idactividad` INT(5) NOT NULL AUTO_INCREMENT,
    `idusuario` INT(5) NOT NULL,
    `actividad` VARCHAR(100) NOT NULL,
    `cliente` varchar(100) COLLATE utf8_spanish_ci,
    `comentarios` varchar(300) COLLATE utf8_spanish_ci NOT NULL,
    `fecha_inicio` datetime NOT NULL,
    `fecha_fin` datetime NOT NULL,
    `precio_estimado` float NOT NULL,
    `horas_estimadas` int(3) NOT NULL,
    `habilitado` tinyint(4) NOT NULL DEFAULT '1',

    PRIMARY KEY (idactividad),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario)
);

DROP TABLE IF EXISTS `tareas`;
CREATE TABLE `timetracker`.`tareas` (
    `idtarea` INT(5) NOT NULL AUTO_INCREMENT,
    `idactividad` INT(5) NOT NULL,
    `nombre` VARCHAR(200) NOT NULL,
    `descripcion` MEDIUMTEXT,
    `precio_x_hora` float NOT NULL DEFAULT '25',

    PRIMARY KEY (idtarea),
    FOREIGN KEY (idactividad) REFERENCES actividades(idactividad)
);

DROP TABLE IF EXISTS `registros`;
CREATE TABLE `timetracker`.`registros` (
    `idregistro` INT(10) NOT NULL AUTO_INCREMENT,
    `idtarea` INT(5) NOT NULL,
    `fecha_inicio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_fin` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `duracion` INT(5) NOT NULL,
    `lugar` VARCHAR(100),
    `comentarios` VARCHAR(300),
    `descripcion` MEDIUMTEXT,

    PRIMARY KEY (idregistro),
    FOREIGN KEY (idtarea) REFERENCES tareas(idtarea)
);


INSERT INTO usuarios (usuario,clave,email,fecha_registro) VALUES ('Eorhed','Eorhed123','eorhed@eorhed.com','2021-10-19');
INSERT INTO usuarios (usuario,clave,email,fecha_registro) VALUES ('Zephyr','Zephyr123','zephyr@zephyr.com','2021-10-19');

CREATE USER 'TT_user'@'localhost' IDENTIFIED BY 'KljW92_8';
GRANT USAGE,SELECT,INSERT,UPDATE,DELETE ON timetracker.* TO 'TT_user'@'localhost';
