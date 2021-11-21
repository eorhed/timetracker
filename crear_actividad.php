<?php require_once "app/views/header.php"; ?>

<?php 
    // Controla la sesión de usuario. Si no está logueado o no tiene cookie le devuelve a login
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
        
?>

<?php 
    if (isset($_POST["crear"]))
    {
        // Limpiamos los datos
        
        $actividad = array(
            "actividad" => filter_input(INPUT_POST, "actividad", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "comentarios" => filter_input(INPUT_POST, "comentarios", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "precio_x_hora" => filter_input(INPUT_POST, "precio_x_hora", FILTER_SANITIZE_NUMBER_INT),
            "fechaInicio" => $_POST["fechaInicio"],
            "fechaFin" => $_POST["fechaFin"],
            "idusuario" => Session::get("idusuario")
        );

        require_once "app/libs/php/actividades_model.php";
        $db = new Actividades_Model("user");

        // Validar datos
        // $respuesta = $db->validarActividad($actividad);
        // if ($respuesta["success"])
            $id = $db->insertarActividadBD($actividad);

        if ($id)
            header("Location: actividades.php");
        else
            echo "<div class='info-error'>No se ha podido insertar la actividad. Int&eacute;ntalo m&aacute;s tarde</div>";
            
    }
    
?>

    
    <main>
        <section id="crear-actividad">
            <div class="container">
                <h1>Nueva actividad</h1>
                <form method="POST" action="crear_actividad.php">
                    <div class="form-crear">
                        <div class="bloque_I">
                            <ul>
                                <li><label for="actividad">Nombre actividad:</label>
                                    <input type="text" id="actividad" name="actividad" maxlength="100" placeholder="Actividad" required />
                                </li>
                                <li><label for="fechaInicio">Fecha Inicio:</label>
                                    <input type="date" id="fechaInicio" name="fechaInicio" min="<?=date("Y-m-d")?>" required />
                                </li>
                                <li><label for="fechaFin">Fecha Fin:</label>
                                    <input type="date" id="fechaFin" name="fechaFin" min="<?=date("Y-m-d")?>" required />
                                </li>
                            </ul>
                        </div>
                        <div class="bloque_D">
                            <ul>
                                <li><label for="comentarios">Comentarios:</label>
                                    <textarea id="comentarios" name="comentarios" maxlength="300" placeholder="Escribe una descripción de la actividad a realizar"></textarea>
                                </li>
                                <li><label for="precio_x_hora">Precio por hora:</label>
                                    <input type="range" step="1" id="precio_x_hora" name="precio_x_hora" min="0" max="200" value="0" placeholder="0" onchange="javascript: actualizarPrecioHora();" /><div id="info_precio_hora">0</div>
                                </li>
                            </ul>
                        </div>
                        <div class="centrado clear paddingT50"><button type="submit" id="crear" name="crear" class="boton bg-primario btn-grande c_blanco negrita">Crear</button></div>
                    </div>
                </form>
            </div>
        </section>

<?php require_once "app/views/footer.php"; ?>

