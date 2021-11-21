<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// error_reporting(0);
?>
<?php 
    require_once "app/views/header.php";
    
    // Controla la sesión de usuario. Si no está logueado o no tiene cookie le devuelve a index
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: index.php");

        
    if (isset($_POST))
    {
        if (isset($_POST["baja"]))
        {
            require_once "app/libs/php/usuarios_model.php";
            require_once "app/libs/php/actividades_model.php";
            require_once "app/libs/php/registroTiempo_model.php";

            $db = new Usuarios_Model("user");
            $resultado = $db->darDeBajaUsuario(Session::get('idusuario'), $_COOKIE["TT_token"]);

            echo "<div class='info-success'>Te has dado de baja correctamente</div>";

            // Destruimos la sesión y le redirigimos a la página de inicio de la app
            Session::destroy();
            setcookie("TT_user","", time()-1);
            setcookie("TT_token","", time()-1);
            header("Location: index.php");

        }
    }
?>
    <main>
        <section id="darse-de-baja">
            <div class="container">
                <h1>Darse de baja de Timetracker</h1>
                
                    <div class="info-warning">Sentimos que tengas que dejarnos</div>
                    
                    <?php 
                        if (isset($_POST)):
                        
                            if (isset($_POST["descarga"])):
                            ?>
                            <div class="tus-datos">
                                <h2>Tus datos en Timetracker</h2>
                                <h3>Usuario</h3>
                                <?php 
                                $idusuario = Session::get('idusuario');
                                require_once "app/libs/php/usuarios_model.php";
                                $db = new Usuarios_Model("user");
                                $usuario = array("idusuario" => $idusuario);
                                $datosUsuario = $db->getOne($usuario);

                                if ($datosUsuario)
                                {
                                    echo "<ul>
                                            <li>Usuario: $datosUsuario->usuario</li>
                                            <li>Email: $datosUsuario->email</li>
                                            <li><hr></li>
                                        </ul>";
                                }
                                else
                                    echo "<div class='info-warning'>Este usuario no existe</div>";
                                ?>
                                <h3>Actividades</h3>

                                <?php
                                require_once "app/libs/php/actividades_model.php";
                                $db = new Actividades_Model("user");
                                $actividades = $db->getActividadesUsuario($idusuario);

                                if (isset($actividades) && is_array($actividades))
                                {
                                    echo "<ul>";
                                    foreach ($actividades as $actividad) {
                                        echo "<li><h4>Actividad: $actividad->actividad</h4></li>
                                            <li>Descripcion: $actividad->comentarios</li>
                                            <li>Fecha creaci&oacute;n: $actividad->fecha_inicio</li>
                                            <li>Precio hora: $actividad->precio_x_hora</li>
                                            <li><hr></li>";
                                    }
                                    echo "</ul>";
                                }
                                else
                                    echo "<div class='info-warning'>No hay actividades registradas por este usuario</div>";

                                ?>

                                <h3>Tareas</h3>

                                <?php
                                require_once "app/libs/php/tareas_model.php";
                                $db = new Tareas_Model("user");
                                $tareas = $db->getTareasUsuario($idusuario);

                                if (isset($tareas) && is_array($tareas))
                                {
                                    echo "<ul>";
                                    foreach ($tareas as $tarea) {
                                        echo "<li>Actividad: $tarea->actividad</li>
                                            <li>Tarea: $tarea->nombre</li>
                                            <li>Descripcion: $tarea->descripcion</li>
                                            <li><hr></li>";
                                    }
                                    echo "</ul>";
                                }
                                else
                                    echo "<div class='info-warning'>No hay tareas registradas por este usuario</div>";

                                ?>
                                <h3>Registros de seguimento</h3>
                                <?php
                                require_once "app/libs/php/registroTiempo_model.php";
                                $db = new RegistroTiempo_Model("user");
                                $seguimientos = $db->getRegistrosSeguimientoUsuario($idusuario);

                                if (isset($seguimientos))
                                {
                                    echo "<ul>";
                                    foreach ($seguimientos as $seguimiento) {
                                        echo "<li>Actividad: $seguimiento->actividad</li>
                                            <li>Tarea: $seguimiento->nombre</li>
                                            <li>Fecha inicio: $seguimiento->fecha_inicio</li>
                                            <li>Fecha fin: $seguimiento->fecha_fin</li>
                                            <li>Duraci&oacute;n: $seguimiento->duracion</li>
                                            <li><hr></li>";
                                    }
                                    echo "</ul>";
                                }
                                else
                                    echo "<div class='info-warning'>No hay registros de tiempo registrados por este usuario</div>";
                                ?>

                            </div>
                            <?php endif; 
                            endif; ?>
                    <div class="opciones-baja">
                        <h2>Te proponemos las siguientes opciones: </h2>
                            <form method="POST" action="darse_de_baja.php">
                                <ul>
                                    <li><button type="submit" id="descarga" name="descarga" class="boton btn-grande bg-primario c_blanco">Ver/guardar todos tus datos</button></li>
                                    <li><button type="submit" id="baja" name="baja" class="boton btn-grande bg-danger c_blanco">Darte de baja</button></li>
                                </ul>
                            </form>
                    </div>
                    
                
            </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>