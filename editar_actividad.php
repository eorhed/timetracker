<?php 
    require_once "app/views/header.php";
    
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
        
?>

<?php 
    if (isset($_POST["editar"]))
    {
        // Limpiamos los datos
        
        $actividad = array(
            "actividad" => filter_input(INPUT_POST, "actividad", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "comentarios" => filter_input(INPUT_POST, "comentarios", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "precio_x_hora" => filter_input(INPUT_POST, "precio_x_hora", FILTER_SANITIZE_NUMBER_INT),
            "fecha_inicio" => filter_input(INPUT_POST, "fecha_inicio", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "fecha_fin" => filter_input(INPUT_POST, "fecha_fin", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "idactividad" => filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT),
            "idusuario" => Session::get("idusuario")
        );

        require_once "app/libs/php/actividades_model.php";
        $db = new Actividades_Model("user");

        // Validar datos
        // $respuesta = $db->validarActividad($actividad);
        // if ($respuesta["success"])
        $resultado = $db->editarActividad($actividad);

        if ($resultado)
            echo "<div class='info-success'>Los datos han sido actualizados correctamente</div>";
        else
            echo "<div class='info-error'>No se ha podido actualizar la actividad. Int&eacute;ntalo m&aacute;s tarde</div>";
            
    }

    if (isset($_POST["borrar"]))
    {
        require_once "app/libs/php/actividades_model.php";
        $db = new Actividades_Model("user");

        $idactividad = filter_input(INPUT_POST, "idactividad", FILTER_SANITIZE_NUMBER_INT);
        $resultado = $db->borrarActividadUsuario($idactividad, Session::get('idusuario')); // Si la actividad no es del usuario no hará nada esta función

        if ($resultado)
            echo "<div class='info-success'>La actividad y las tareas y seguimientos relacionados con ella han sido borradas correctamente. Volviendo a actividades...<meta http-equiv='refresh' content='3;URL=actividades.php'></div>";
        else
            echo "<div class='info-error'>No se ha podido borrar la actividad. Int&eacute;ntalo m&aacute;s tarde</div>";
    }
    
?>

<main>

<?php 

    // Si se llega a editar_actividad.php si pasarle una id por GET redirigirá hacia actividades.php
    if (!isset($_GET["id"]))
    header("Location: actividades.php");

    // Limpiamos el id de actividad pasado en la variable global GET
    $idactividad = filter_var($_GET["id"], FILTER_SANITIZE_NUMBER_INT);

    // Comprobamos que la actividad es de este usuario en concreto
    require_once "app/libs/php/actividades_model.php";
    $db = new Actividades_Model("user");
    $actividad = $db->getActividadUsuario($idactividad, Session::get('idusuario'));

    if ($actividad)
    {
        // Mostramos el formulario de actividad con los datos de esta actividad para editar
        
    ?>

        <section id="crear-actividad">
            <div class="container">
                <h1>Editar actividad <span style='color: #00bfa5'><?= $actividad->actividad ?></span></h1>
                <form method="POST">
                    <div class="form-crear">
                        <div class="bloque_I">
                            <ul>
                                <li><label for="actividad">Nombre actividad:</label>
                                    <input type="text" id="actividad" name="actividad" maxlength="100" placeholder="Actividad" value="<?= isset($actividad->actividad) ? $actividad->actividad : "-" ?>" />
                                </li>
                                <li><label for="fecha_inicio">Fecha Inicio:</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" min="<?=date("Y-m-d")?>" value="<?= isset($actividad->fecha_inicio) ? substr($actividad->fecha_inicio,0,10) : "" ?>" required />
                                </li>
                                <li><label for="fecha_fin">Fecha Fin:</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" min="<?=date("Y-m-d")?>" value="<?= isset($actividad->fecha_fin) ? substr($actividad->fecha_fin,0,10) : "" ?>" required />
                                </li>
                            </ul>
                        </div>
                        <div class="bloque_D">
                            <ul>
                                <li><label for="comentarios">Comentarios:</label>
                                    <textarea id="comentarios" name="comentarios" maxlength="300" placeholder="Escribe una descripción de la actividad a realizar"><?= isset($actividad->comentarios) ? $actividad->comentarios : "-" ?></textarea>
                                </li>
                                <li><label for="precio_x_hora">Precio por hora:</label>
                                    <input type="range" step="1" id="precio_x_hora" name="precio_x_hora" min="0" max="200"  value="<?= isset($actividad->precio_x_hora) ? $actividad->precio_x_hora : "-" ?>" onchange="actualizarPrecioHora();" /><div id="info_precio_hora"><?= isset($actividad->precio_x_hora) ? $actividad->precio_x_hora : "-" ?> €</div>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="idactividad" id="idactividad" value="<?= $_GET["id"] ?>">
                        <div class="centrado clear paddingT50"><button type="submit" id="borrar" name="borrar" class="boton btn-grande bg-danger c_blanco" onclick="return confirm('Si sigues borrarás la actividad y las tareas y registros asociados a esta')">Borrar</button><button type="submit" id="editar" name="editar" class="boton bg-primario btn-grande c_blanco negrita">Actualizar</button></div>
                    </div>
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

