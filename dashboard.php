<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// error_reporting(0);
?>
<?php 
    require_once "app/views/header.php";
    
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: index.php");
        
        require_once "app/libs/php/dashboard_model.php"; 
?>
    
    <main>

        <section id="dashboard">
            <div class="container">
                <h1>Escritorio</h1>
                <?php 

                    // //Sesión
                    // echo "ID: ". Session::get("idusuario")."<br>";
                    // echo "Usuario: ". Session::get("usuario")."<br>";
                    // echo "Email: ". Session::get("email")."<br>";
                    // echo "Foto: ". Session::get("foto")."<br>";
                    // echo "Last Activity: ". Session::get("last_activity")."<br>";
                    // echo "Created: ". Session::get("created")."<br>";

                    $idusuario = Session::get("idusuario");
                    $db = new Dashboard_Model("user"); 
                    $num_actividades = $db->getNumTotalActividadesUsuario($idusuario);
                    $num_tareas = $db->getNumTotalTareasUsuario($idusuario);
                    $horas_registradas = $db->getNumTotalHorasRegistradasUsuario($idusuario);
                    $dinero_generado = $db->getTotalDineroGenerado($idusuario);
                ?>
                <ul>
                    <li>
                        <?php
                            if (isset($num_actividades))
                                echo "<a href='actividades.php'><span class='numero-dashboard'>".$num_actividades."</span><h2>actividades registradas</h2></a>";
                        ?>
                    </li>
                    <li><?php 
                        if (isset($num_tareas))
                            echo "<a href='tareas.php'><span class='numero-dashboard'>".$num_tareas."</span><h2>tareas registradas</h2></a>";
                    ?></li>
                    <li><?php 
                        if (isset($horas_registradas))
                            echo "<a href='informes.php'><span class='numero-dashboard'>".$horas_registradas."</span><h2>horas registradas</h2></a>";

                    ?></li>
                    <li><?php 
                        if (isset($dinero_generado))
                            echo "<a href='informes.php'><span class='numero-dashboard'>".$dinero_generado."</span><h2>euros generados</h2></a>";
                    ?></li>
                </ul>
            </div>
        </section>
        <section id="ultimos-registros">
            <div class="container">
                <h2>Últimos registros de seguimiento</h2>
                <?php 
                    require_once "app/libs/php/registroTiempo_model.php";
                    $db = new RegistroTiempo_Model("user");
                    $registros = $db->getRegistrosSeguimientoUsuario(Session::get('idusuario'), 10);
                    
                    if (isset($registros) && is_array($registros))
                    {
                        echo "<ul>";
                        $cont=1;
                        foreach ($registros as $registro) 
                        {
                            // Convertimos segundos a Horas, minutos y segundos
                            $horas = floor($registro->duracion / 3600);
                            $minutos = floor(($registro->duracion - ($horas * 3600)) / 60);
                            $segundos = $registro->duracion - ($horas * 3600) - ($minutos * 60);
                            
                            echo "<li><div class='num-registro'>$cont</div><div class='actividad'>$registro->actividad</div><div class='tarea'>$registro->nombre</div><div class='duracion'>$horas h $minutos min $segundos s</div><div class='fecha-inicio'>$registro->fecha_inicio</div></li>";
                            $cont++;
                        }
                        echo "<ul>";
                    }
                    else
                        echo "<div class='info-warning'>No existen registros de seguimiento</div>";
                ?>
            </div>
        </section>
    </main>   
    <?php require_once "app/views/footer.php"; ?>