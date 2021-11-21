<?php require_once "app/views/header.php"; ?>
<?php require_once "app/config/mensajes.php"; ?>

<?php 
    require_once "app/views/header.php";
    
    // Controla la sesión de usuario. Si no está logueado o no tiene cookie le devuelve a login
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
        
?>
    <main>
        <section id="tareas">
            <div class="container">
                <h1>Tareas</h1>
                <div class="lista-tareas">
                    <button class="boton bg-primario btn-pequeno c_blanco negrita floatR">
                        <a href="crear_tarea.php">
                            <img src="app/assets/img/file-earmark-plus2.png" alt="Imagen nuevo archivo">
                            Nueva tarea
                        </a>
                    </button>
                    <div class="clear"></div>

                    <h2>Lista de tareas</h2>
                
                    <ul>
                        <?php
                            require_once "app/libs/php/tareas_model.php";
                            $db = new Tareas_Model("user");
                            $tareas = $db->getTareasUsuario(Session::get('idusuario'));

                            $cont = 1;
                            if (!empty($tareas))
                            {
                                foreach ($tareas as $tarea) {
                                    
                                    echo "<li><a href='editar_tarea.php?id={$tarea->idtarea}'><div class='num-actividad'>$cont</div><div class='actividad'>$tarea->nombre</div><div class='cabecera-info'><span>$tarea->actividad</span></div></a></li>";

                                    $cont++;
                                }
                            }
                            else
                                echo "<li>No hay tareas creadas</li>";
                        ?>
                            <div class="num-resultados floatR"><?php echo (isset($tareas) && is_array($tareas)) ? count($tareas) : 0 ?> resultados</div>
                    </ul>
                </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>