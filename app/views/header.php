<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// error_reporting(0);

    require_once "sesion.php"; 
    Session::init();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Timetracker - Aplicación web de gestión y seguimiento de actividades y proyectos</title>
        <meta name="robots" content="all" />
        <meta name="keywords" content="time tracking seguimiento tareas gestion proyectos" />
        <meta name="description" content="Gestión y seguimiento de tareas en proyectos" />
        <link rel="shortcut icon" href="app/assets/img/favicon.png">
        <link rel="stylesheet" type="text/css" media="screen" href="app/assets/css/main.css" />
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,400,700" rel="stylesheet">
        <script src="app/libs/js/chart.min.js"></script>
    </head>
    <body>
        <header>
            <div class="container-fluid">
                <div id="logo"><a href="index.php"><img src="app/assets/img/favicon.png" alt="Logo TimeTracker"><h1>TIMETRACKER</h1></a></div>
                <div id="menu-principal">
                    <?php if (!Session::isLogged()): ?>
                        <ul class="menu-anonimo">
                            <li><a href="registro.php">Registro</a></li>
                            <li><a href="login.php">Iniciar sesión</a></li>
                        </ul>
                    <?php endif; ?>
                    <?php if (Session::isLogged()): ?>
                        <ul class="menu-usuario">
                            <li><a href="dashboard.php">Inicio</a></li>
                            <li><a href="actividades.php">Actividades</a></li>
                            <li><a href="tareas.php">Tareas</a></li>
                            <li><a href="trackear.php">Trackear</a></li>
                            <li><a href="informes.php">Informes</a></li>
                            <li><a href="contacto.php">Contacto</a></li>
                        </ul>
                        <ul class="menu-prefs-usuario">
                            <li><a href="darse_de_baja.php"><span class="usuario-logueado"><img src="app/assets/img/<?php echo Session::get('foto');?>" alt="foto-usuario"><?php echo Session::get('usuario');?></span></a></li>
                            <?php if (Session::isAdmin()):?>
                                <li><a href="#">Administrar</a></li>
                            <?php endif; ?>
                            <li><a href="salir.php"><img src="app/assets/img/box-arrow-right.svg" alt="Icono de salir"> Salir</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </header>