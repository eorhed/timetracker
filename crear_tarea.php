<?php require_once "app/views/header.php"; ?>

<?php 
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
?>

<?php 
    if (isset($_POST["crear"]))
    {
        // Limpiamos los datos
        
        $tarea = array(
            "idactividad" => filter_input(INPUT_POST, "idactividad", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "nombre" => filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "descripcion" => filter_input(INPUT_POST, "descripcion", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "idusuario" => Session::get("idusuario")
        );

        require_once "app/libs/php/tareas_model.php";
        $db = new Tareas_Model("user");

        // Validar datos
        // $respuesta = $db->validarActividad($actividad);
        // if ($respuesta["success"])
            $db->insertarTareaBD($tarea);
            
    }
    
?>
    <main>
        <section id="crear-tarea">
            <div class="container">
                <h1>Nueva tarea</h1>
                <form action="crear_tarea.php" method="POST">
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
                               <?php foreach ($actividades as $actividad):
                                        echo "<option value=" . $actividad->idactividad . ">" . $actividad->actividad . "</option>"; 
                                     endforeach;
                                ?>
                            </select>
                        </li>
                        <li><label for="nombre">Nombre tarea:</label>
                            <input type="text" name="nombre" maxlength="100" />
                        </li>
                        <li><label for="descripcion">Descripción:</label>
                            <input type="text" name="descripcion" maxlength="200" />
                        </li>
                    </ul>
                    <div class="centrado"><button type="submit" id="crear" name="crear" class="boton bg-primario btn-grande c_blanco negrita">Crear</button></div>
                    <?php endif; ?>
                </form>
            </div>
        </section>

<?php require_once "app/views/footer.php"; ?>

