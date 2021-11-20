<?php require_once "app/views/header.php"; ?>

<?php 
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
?>

<main>
    <section id="actividades">
        <div class="container">
            <h1>Actividades</h1>
            <div class="lista-actividades">
                <button class="boton bg-primario btn-pequeno c_blanco negrita floatR">
                    <a href="crear_actividad.php">
                        <img src="app/assets/img/file-earmark-plus2.png" alt="Imagen nuevo archivo">
                        Nueva actividad
                    </a>
                </button>
                <div class="clear"></div>

                <h2>Lista de actividades</h2>
                
                <ul>
                    <?php
                        require_once "app/libs/php/actividades_model.php";
                        $db = new Actividades_Model("user");
                        $actividades = $db->getActividadesUsuario(Session::get('idusuario'));

                        $cont = 1;
                        if (!empty($actividades))
                        {
                            foreach ($actividades as $actividad) {
                                $fecha_inicio = substr($actividad->fecha_inicio,8,2)."/".substr($actividad->fecha_inicio,5,2)."/".substr($actividad->fecha_inicio,0,4);
                                $fecha_fin = substr($actividad->fecha_fin,8,2)."/".substr($actividad->fecha_fin,5,2)."/".substr($actividad->fecha_fin,0,4);
                                
                                echo "<li><a href='editar_actividad.php?id={$actividad->idactividad}'><div class='num-actividad'>$cont</div><div class='actividad'>$actividad->actividad</div><div class='cabecera-info'><span>$fecha_inicio</span><span>$fecha_fin</span></div></a></li>";

                                $cont++;
                            }
                        }
                        else
                            echo "<li>No hay actividades creadas</li>";
                    ?>
                        <div class="num-resultados floatR"><?php echo (isset($actividades) && is_array($actividades)) ? count($actividades) : 0 ?> resultados</div>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php require_once "app/views/footer.php"; ?>