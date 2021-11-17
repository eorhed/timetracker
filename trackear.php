<?php require_once "app/views/header.php"; ?>

<?php 
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
?>


    
    <main>
        <section id="trackear">
            <div class="container">
                <h1>Trackear</h1>
                <?php 
                    require_once 'app/libs/php/actividades_model.php'; 
                    $db = new Actividades_Model('user');
                    $actividades = $db->getActividadesUsuario(Session::get('idusuario'));

                    $id_primera_actividad = $db->getPrimeraActividadConTareasUsuario(Session::get('idusuario'));
                    

                    if (!empty($actividades) && is_integer($actividades) && $actividades == 0):
                        echo "<div class='info-warning'>No tienes ninguna actividad creada todav&iacute;a.<br/> Para realizar un seguimiento de tiempo es necesario una actividad y una tarea. <br/>Crea tu primera actividad pulsando <a href='crear_actividad.php'>aqu&iacute;</a></div>";
                    elseif (!empty($id_primera_actividad) && is_integer($id_primera_actividad) && $id_primera_actividad == 0):
                        echo "<div class='info-warning'>No tienes ninguna tarea creada todav&iacute;a.<br/> Para realizar un seguimiento de tiempo es necesario una actividad y una tarea. <br/>Crea tu primera actividad pulsando <a href='crear_tarea.php'>aqu&iacute;</a></div>";
                    else:
                ?>
                
                
                    <section class="form-trackear">
                        <div class="bloque_I">
                            <ul>
                                <li>
                                    <label for="actividad">Actividad</label>
                                    <select name="idactividad" id="idactividad" onChange="javascript:actualizarTareasAlCambiarActividad();">
                                        <?php
                                            $cont = 1;
                                            foreach ($actividades as $actividad):
                                                if ($cont == 1){
                                                    $idActividadTarea = $actividad->idactividad;    // Para luego mostrar las tareas de la primera actividad encontrada del usuario
                                                    echo "<option value='$actividad->idactividad' selected>$actividad->actividad</option>";
                                                }
                                                else
                                                    echo "<option value='$actividad->idactividad'>$actividad->actividad</option>";
                                                
                                                $cont++;
                                        ?>
                                        
                                        <?php endforeach; ?>
                                    </select>
                                </li>
                                <li>
                                    <label for="tarea">Tarea</label>
                                        
                                        <?php 
                                            require_once "app/libs/php/tareas_model.php";
                                            $db = new Tareas_Model("user");
                                            $tareas = $db->getTareasActividad($id_primera_actividad);

                                            if (!empty($tareas) && is_integer($tareas) && $tareas == 0):
                                                echo "<div class='info-warning'>No tienes ninguna tarea creada todav&iacute;a.<br/> Para realizar un seguimiento de tiempo es necesario una actividad y una tarea. <br/>Crea tu primera actividad pulsando <a href='crear_tarea.php'>aqu&iacute;</a></div>";
                                            else:
                                        ?>
                                            <select name="idtarea" id="idtarea">
                                            <?php
                                                $cont = 1;
                                                foreach ($tareas as $tarea):
                                                    if ($cont == 1)
                                                        echo "<option value='$tarea->idtarea' selected>$tarea->nombre</option>";
                                                    else
                                                        echo "<option value='$tarea->idtarea'>$tarea->nombre</option>";

                                                    $cont++;
                                                ?>
                                                
                                            <?php 
                                                endforeach; 
                                            endif;
                                            ?>
                                            </select>
                                </li>
                                <!-- <li> -->
                                    <!-- <label for="actividad_lucrativa">Actividad lucrativa</label>
                                    <input type="radio" name="actividad_lucrativa" id="actividad_lucrativa" value="Sí"> Sí
                                    <input type="radio" name="actividad_lucrativa" id="actividad_lucrativa" value="No"> No -->

                                    <!-- <label for="precio_hora">Precio por hora</label>
                                    <input type="range" id="precio_hora" name="precio_hora" min=0 max=50 value="0" class="form-input-text" placeholder="Usuario" onLoad="javascript:actualizarPrecioHora();" onChange="javascript:actualizarPrecioHora();" />
                                    <span id="info_precio_hora">0</span> € -->
                                <!-- </li> -->
                            </ul>
                        </div>
                        <div class="bloque_D">
                            <ul>
                                <li>
                                    <label for="comentarios">Comentarios</label>
                                    <textarea name="comentarios" id="comentarios" cols="30" rows="10" placeholder="Escribe aquí comentarios relacionados con el seguimiento de la tarea a realizar"></textarea>
                                </li>
                            </ul>
                        </div>
                        <div class="clear centrado"><button type="button" class="boton btn-grande bg-primario c_blanco" onclick="javascript: comenzarSeguimiento();">Comenzar</button></div>
                    </section>

                <section id="seguimientos">
                    <div class="container">
                        <form method="POST" action="trackear.php">
                            <div id="cronometro">
                                <div class="marcador">00:00:00</div>
                                <div class="acciones-cronometro">
                                    <img class="btn-guardar-tiempo" onclick="guardarSeguimiento();" src="app/assets/img/stop-circle.svg" alt="Icono de pausar multimedia">
                                    <img class="btn-reproducir-tiempo" onclick="conmutarSeguimiento();" src="app/assets/img/play-circle.svg" alt="Icono de reproducir multimedia">
                                    <img class="btn-pausar-tiempo" onclick="conmutarSeguimiento();" src="app/assets/img/pause-circle.svg" alt="Icono de pausar multimedia">
                                </div>
                            </div>
                        
                            <div class="lista-seguimientos">
                                <h2>Seguimientos</h2>
                                <ul>
                                </ul>
                            </div>
                            <div class="acciones">
                                <button type="button" class="boton btn-default btn-pausar" onclick="conmutarSeguimiento();">
                                <span><img src="app/assets/img/pause-circle.svg" alt="Icono de pausa" style="vertical-align:text-top;"></span> Pausar
                                </button>
                                <button type="button" class="boton bg-primario btn-guardar" onclick="guardarSeguimiento();">
                                    <span><img src="app/assets/img/stop-circle.svg" alt="Icono de parar reproducci&oacute;n" style="vertical-align:text-top;"></span> Finalizar y guardar
                                </button>
                                <button type="button" class="boton btn-primary btn-comenzar2" onclick="comenzarSeguimiento();">
                                        <span class="fa fa-play"></span> Nuevo seguimiento
                                </button>
                            </div>
                        </div>
                        </form>
                </section>
                </form>
                <?php endif; ?>
            </div>
    </main>

<?php require_once "app/views/footer.php"; ?>