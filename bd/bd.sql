DROP DATABASE IF EXISTS `timetracker`;
CREATE DATABASE IF NOT EXISTS `timetracker` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

use timetracker;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `timetracker`.`usuarios` (
    `idusuario` INT(5) NOT NULL AUTO_INCREMENT,
    `usuario` VARCHAR(20) NOT NULL,
    `hash` VARCHAR(128) NOT NULL,
    `token` VARCHAR(128),
    `tipo_usuario` VARCHAR(7) NOT NULL DEFAULT 'usuario',
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
    `precio_x_hora` float NOT NULL,
    `precio_estimado` float NOT NULL,
    `horas_estimadas` int(3) NOT NULL,
    `habilitado` tinyint(4) NOT NULL DEFAULT '1',

    PRIMARY KEY (idactividad),
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `tareas`;
CREATE TABLE `timetracker`.`tareas` (
    `idtarea` INT(5) NOT NULL AUTO_INCREMENT,
    `idactividad` INT(5) NOT NULL,
    `nombre` VARCHAR(200) NOT NULL,
    `descripcion` MEDIUMTEXT,
    `precio_x_hora` float NOT NULL DEFAULT '25',

    PRIMARY KEY (idtarea),
    FOREIGN KEY (idactividad) REFERENCES actividades(idactividad) ON DELETE CASCADE
);

DROP TABLE IF EXISTS `registros`;
CREATE TABLE `timetracker`.`registros` (
    `idregistro` INT(10) NOT NULL AUTO_INCREMENT,
    `idtarea` INT(5) NOT NULL,
    `fecha_inicio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `fecha_fin` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `duracion` INT(5) NOT NULL DEFAULT 0,
    `comentarios` VARCHAR(300),
    `descripcion` MEDIUMTEXT,

    PRIMARY KEY (idregistro),
    FOREIGN KEY (idtarea) REFERENCES tareas(idtarea) ON DELETE CASCADE
);

INSERT INTO usuarios (usuario,email,hash,tipo_usuario) VALUES ('admin', 'admin@admin.com','b164e180d26b48f714a0cb62df77d984490baca3bc19e82b9ba178f3f8f463c4c9aff9f245ac91202f6e7a29addb588925453259415c35ad253885cb07afcb87','admin');


/* Tipo usuario: Admin */
GRANT USAGE ON timetracker.* TO 'TT_admin'@'localhost' IDENTIFIED BY PASSWORD '*37A9F979A6B7FF115682F5CDFD22223DE3D21DBD';

GRANT SELECT, INSERT, UPDATE, DELETE ON `timetracker`.* TO 'TT_admin'@'localhost';

/* Tipo usuario: Usuario */
GRANT USAGE ON timetracker.* TO 'TT_user'@'localhost' IDENTIFIED BY PASSWORD '*5B277906B210C41D6ABD2D560D44B1E4D32F478B';

GRANT SELECT, INSERT, UPDATE, DELETE ON `timetracker`.* TO 'TT_user'@'localhost';

/* Tipo usuario: Anonimo */
GRANT USAGE ON timetracker.* TO 'TT_anonymous'@'localhost' IDENTIFIED BY PASSWORD '*A0916871DFF69C47D9E85765C5D482BD978D9587';

GRANT SELECT, INSERT ON `timetracker`.usuarios TO 'TT_anonymous'@'localhost';


