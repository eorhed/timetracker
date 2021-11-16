<?php require_once "app/views/header.php"; ?>
<?php require_once "app/config/mensajes.php"; ?>
    
    <main>
        <section id="tareas">
            <div class="container">
                <h1>Tareas</h1>
                <form method="POST" action="crear_tarea.php">
                    <button class="boton bg-primario btn-pequeno c_blanco negrita floatR">
                        <a href="crear_tarea.php">
                            <img src="app/assets/img/file-earmark-plus2.png" alt="Imagen nuevo archivo">
                            Nueva tarea
                        </a>
                    </button>
                    <div class="clear"></div>

                    <?php 
                        require_once "app/libs/php/tareas_model.php";
                        $db = new Tareas_Model("user");
                        $tareas = $db->getTareasUsuario(Session::get('idusuario'));
                        
                    ?>
                    <div class="tabla">
                        <table>
                            <thead>
                                <tr>
                                    <td width="20">#</td>
                                    <td>ACTIVIDAD</td>
                                    <td>TAREA</td>
                                    <td>DESCRIPCI&Oacute;N</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (isset($tareas) && is_integer($tareas) && $tareas == 0)
                                        echo "<tr><td colspan='4'>No hay tareas creadas</td></tr>";
                                    else{
                                        $cont = 1;
                                        foreach ($tareas as $tarea) {
                                            echo "<tr><td width'20'>$cont</td><td width='100'>$tarea->actividad</td><td width='100'>$tarea->nombre</td><td width='200'>$tarea->descripcion</td></tr>";
                                            
                                            $cont++;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>