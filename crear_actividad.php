<?php require_once "app/views/header.php"; ?>
<?php require_once "app/config/mensajes.php"; ?>
<?php 
    if (isset($_POST["crear"]))
    {
        // Limpiamos los datos
        
        $actividad = array(
            "actividad" => filter_input(INPUT_POST, "actividad", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "cliente" => filter_input(INPUT_POST, "cliente", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "comentarios" => filter_input(INPUT_POST, "comentarios", FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            "precio_x_hora" => filter_input(INPUT_POST, "precio_x_hora", FILTER_SANITIZE_NUMBER_INT),
            "fecha_inicial" => date("Y-m-d H:i:s"),
            "idusuario" => Session::get("idusuario")
        );

        require_once "app/libs/php/actividades_model.php";
        $db = new Actividades_Model("user");

        // Validar datos
        // $respuesta = $db->validarActividad($actividad);
        // if ($respuesta["success"])
            $db->insertarActividadBD($actividad);
            
    }
    
?>

    
    <main>
        <section id="crear-actividad">
            <div class="container">
                <h1>Nueva actividad</h1>
                <form method="POST" action="crear_actividad.php">

                    <ul>
                        <li><label for="actividad">Nombre actividad:</label>
                            <input type="text" name="actividad" maxlength="100" placeholder="Actividad" />
                        </li>
                        <li><label for="cliente">Cliente:</label>
                            <input type="text" name="cliente" placeholder="Cliente" />
                        </li>
                        <li><label for="comentarios">Comentarios:</label>
                            <textarea name="comentarios" maxlength="300" placeholder="Escribe una descripción de la actividad a realizar"></textarea>
                        </li>
                        <li><label for="fechaInicio">Fecha Inicio:</label>
                            <input type="date" name="fechaInicio" required min="<?=date("Y-m-d")?>" />
                        </li>
                        <li><label for="fechaFin">Fecha Fin:</label>
                            <input type="date" name="fechaFin" required min="<?=date("Y-m-d")?>" />
                        </li>
                        <li><label for="precio_x_hora">Precio por hora:</label>
                            <input type="range" step="1" name="precio_x_hora" min="0" max="1000" placeholder="0" />
                        </li>

                        <li><label for="precioEstimado">Precio estimado:</label>
                            <input type="range" step="1" name="precioEstimado" min="0" max="100000" placeholder="0" />
                        </li>
                    
                    <!-- <li><label for="habilitado">Habilitado</label>
                    <input type="checkbox" name="habilitado" checked /></li> -->
                    </ul>
                    <div class="centrado"><button type="submit" id="crear" name="crear" class="boton bg-primario btn-grande c_blanco negrita">Crear</button></div>
                        
                </form>
            </div>
        </section>

<?php require_once "app/views/footer.php"; ?>

