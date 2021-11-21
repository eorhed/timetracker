<?php require_once "app/views/header.php"; ?>

<?php 

    // Controla la sesión de usuario. Si no está logueado o no tiene cookie le devuelve a login
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
        
?>

<?php 
    if (isset($_GET["id"]))
    {
        // Comprobar que esta tarea pertenece al usuario
        require_once "app/libs/php/tareas_model.php";
        $idtarea = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);    // Limpiamos la variable pasada por GET

        $db = new Tareas_Model("user");
        if (!$db->perteneceTareaAUsuario($idtarea, Session::get('idusuario')))
            echo "<div class='info-error'>No existe la tarea o no tienes permiso para visualizarla. Regresando...</div><br><meta http-equiv='refresh' content='5;URL=tareas.php'>";
    }

    if (isset($_POST["editar"]))
    {
        // Limpiamos los datos
        
        $tarea = array(
            "nombre" => filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "descripcion" => filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "idtarea" => filter_input(INPUT_POST, "idtarea", FILTER_SANITIZE_NUMBER_INT),
            "idactividad" => filter_input(INPUT_POST, "idactividad", FILTER_SANITIZE_NUMBER_INT),
            "idusuario" => Session::get("idusuario")
        );

        if (!$db->perteneceTareaAUsuario($tarea["idtarea"], Session::get('idusuario')))
            echo "<div class='info-error'>No existe la tarea o no tienes permiso para visualizarla. Regresando...</div><br><meta http-equiv='refresh' content='5;URL=tareas.php'>";
        else
        {
            require_once "app/libs/php/tareas_model.php";
            $db = new Tareas_Model("user");

            // Validar datos
            // $respuesta = $db->validarTarea($tarea);
            // if ($respuesta["success"])
            $resultado = $db->editarTarea($tarea);

            if ($resultado)
                echo "<div class='info-success'>Los datos han sido actualizados correctamente</div>";
            else
                echo "<div class='info-error'>No se ha podido actualizar la tarea. Int&eacute;ntalo m&aacute;s tarde</div>";
        }
    }

    // Si se ha pulsado el botón borrar comprobará que esa tarea pertenece al usuario, 
    //de ser así, la borrará junto con los seguimientos relacionados con ella
    if (isset($_POST["borrar"]))
    {
        
        require_once "app/libs/php/tareas_model.php";
        $db = new Tareas_Model("user");

        // Limpiamos la variable del formulario pasada por POST
        $idtarea = filter_input(INPUT_POST, "idtarea", FILTER_SANITIZE_NUMBER_INT);

        // Comprobamos que la tarea pertenece al usuario logueado
        if ($db->perteneceTareaAUsuario($idtarea, Session::get('idusuario')))
        {
            $resultado = $db->borrarTareaUsuario($idtarea, Session::get('idusuario'));  // Si la actividad no es del usuario no hará nada esta función

            if ($resultado)
                echo "<div class='info-success'>La tarea y seguimientos relacionados con ella han sido borradas correctamente. Volviendo a tareas...<meta http-equiv='refresh' content='3;URL=tareas.php'></div>";
            else
                echo "<div class='info-error'>No se ha podido borrar la actividad. Int&eacute;ntalo m&aacute;s tarde</div>";
        }
        else
            echo "<div class='info-error'>No existe la actividad o no tienes permiso para visualizarla. Regresando...</div><br>
              <meta http-equiv='refresh' content='5;URL=index.php'>";
    }
    
?>

<main>

<?php 

    // Si se llega a editar_tarea.php si pasarle una id por GET redirigirá hacia tareas.php
    if (!isset($_GET["id"]))
        header("Location: tareas.php");

    $idtarea = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);

    // Comprobamos que la tarea es de este usuario en concreto
    require_once "app/libs/php/tareas_model.php";
    $db = new Tareas_Model("user");
    $tarea = $db->getTareaUsuario($idtarea, Session::get('idusuario'));

    if (!empty($tarea))
    {
        // Mostramos el formulario de actividad con los datos de esta actividad para editar
        
    ?>

    <section id="crear-tarea">
        <div class="container">
            <h1>Nueva tarea</h1>
            <form method="POST">
                <?php 
                    require_once "app/libs/php/actividades_model.php";
                    $db = new Actividades_Model("user");
                    $actividades = $db->getActividadesUsuario(Session::get('idusuario'));

                    if ($actividades == 0):
                        echo "<div class='info-warning'>No has creado ninguna actividad todavía.<br/> Crea tu primera actividad pulsando <a href='crear_actividad.php'>aqui</a></div>";
                    else:
                ?>
                <ul>
                    <li>
                        <label for="idactividad">Actividad:</label>
                        <select name="idactividad">
                            <?php 
                            
                            foreach ($actividades as $actividad)
                            {
                                if ($actividad->idactividad == $tarea->idactividad)
                                    echo "<option value=" . $actividad->idactividad . " selected>" . $actividad->actividad . "</option>"; 
                                else
                                    echo "<option value=" . $actividad->idactividad . ">" . $actividad->actividad . "</option>"; 
                            }
                                

                            ?>
                        </select>
                    </li>
                    <li><label for="nombre">Nombre tarea:</label>
                        <input type="text" id="nombre" name="nombre" maxlength="100" value="<?= isset($tarea->nombre) ? $tarea->nombre : "" ?>" />
                    </li>
                    <li><label for="descripcion">Descripción:</label>
                        <input type="text" id="descripcion" name="descripcion" maxlength="200" value="<?= isset($tarea->descripcion) ? $tarea->descripcion : "" ?>" />
                    </li>
                    <li>
                        <input type="hidden" id="idtarea" name="idtarea" value="<?= isset($tarea->idtarea) ? $tarea->idtarea : "" ?>">
                    </li>
                </ul>
                <div class="centrado"><button type="submit" id="borrar" name="borrar" class="boton btn-grande bg-danger c_blanco" onclick="return confirm('Si sigues borrarás la tarea y seguimientos asociados a esta')">Borrar</button> <button type="submit" id="editar" name="editar" class="boton bg-primario btn-grande c_blanco negrita">Actualizar</button></div>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <?php 
    }
    else if (isset($_POST["borrar"]))
        echo "";
    else
        echo "<div class='info-error'>No existe la actividad o no tienes permiso para visualizarla. Regresando...</div><br>
              <meta http-equiv='refresh' content='5;URL=index.php'>";
        
?>
    
        

<?php require_once "app/views/footer.php"; ?>

