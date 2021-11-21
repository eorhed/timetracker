<?php require_once "app/views/header.php";

    // Controla la sesión de usuario. Si no está logueado o no tiene cookie le devuelve a login
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
?>

<main>
    <section id="informes">
        <div class="container">
            <h1>Informe</h1>
            <div class="cabecera">Lista de informes</div>
            <div class="lista-informes">
                <ul>
                <?php
                    require_once "app/libs/php/informes_model.php";
                    $db = new Informes_Model("user");
                    $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);
                    $tareas = $db->getTareasActividadUsuario(Session::get("idusuario"), $id);

                    if (!empty($tareas))
                    {
                        foreach ($tareas as $tarea) {
                            echo "<li><div class='actividad'>$tarea->nombre</div><div class='cabecera-info'><span>$tarea->duracion_total</span><span>" . round($tarea->precio_total,2) . "€</span></div></li>";
                        }
                    }
                    else
                        echo "<li><div class='num-resultados'>No hay registros de trackeo de esta actividad</div></li>";
                ?>
                    <div class="num-resultados floatR"><?php echo count($tareas) ?> resultados</div>
                </ul>
            </div>
        </div>
        <div class="graficos-informe">
            <div class="container">
            <h2>Distribución de horas</h2>
            <div>
                <canvas id="myChart"></canvas>
                
                <script>
                    // === include 'setup' then 'config' above ===

                    const data = {
                    labels: [
                        <?php 
                            $cont = 1;
                            foreach ($tareas as $tarea) {
                                if ($cont == 1)
                                    echo "'".$tarea->nombre."'";
                                else
                                    echo ",'".$tarea->nombre."'";
                                $cont++;
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Distribución de esfuerzo en tareas expresado en horas',
                        data: [
                            <?php 
                                $cont = 1;
                                foreach ($tareas as $tarea) {
                                    if ($cont == 1)
                                        echo $tarea->duracion;
                                    else
                                        echo ",".$tarea->duracion;
                                    $cont++;
                                }
                            ?>
                        ],
                        backgroundColor: [
                        'rgb(255, 106, 0)',
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(0, 191, 165)',
                        'rgb(255, 205, 86)'
                        ],
                        hoverOffset: 4
                    }]
                    };
                                    const config = {
                                                        type: 'pie',
                                                        data: data,
                                                        options: {}
                                    };

                    const myChart = new Chart(
                        document.getElementById('myChart'),
                        config
                    );
                </script>

                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once "app/views/footer.php"; ?>