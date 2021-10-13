<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// error_reporting(0);
?>
    <?php require_once "app/views/header.php"; ?>
    <?php require_once "app/libs/php/model.php"; ?>
    <?php require_once "app/libs/php/dashboard_model.php"; ?>
    
    <main>

        <section id="dashboard">
            <div class="container">
                <?php 
                    $_SESSION["usuario"]["id"] = 2 ;
                    $idusuario = $_SESSION["usuario"]["id"];
                    $db = new Dashboard_Model("user"); 
                  
                    $num_actividades = $db->getNumTotalActividadesUsuario($idusuario);
                    $tareas = $db->getNumTotalTareasUsuario($idusuario);
                    $horas_registradas = $db->getNumTotalHorasRegistradasUsuario($idusuario);
                    $dinero_generado = $db->getTotalDineroGenerado($idusuario);
                ?>
                <ul>
                    <li>
                        <?php
                            if (isset($num_actividades)){
                                echo $num_actividades."<h2>actividades registradas</h2>";
                            }
                            else
                            {
                                echo "Error BD?";
                            }
                        ?>
                    </li>
                    <li><?php 
                        if (isset($tareas)){
                            echo $tareas."<h2>tareas registradas</h2>";
                        }
                        else
                        {
                            echo "Error BD?";
                        }
                    ?></li>
                    <li><?php 
                        if (isset($horas_registradas)){
                            echo $horas_registradas."<h2>horas registradas</h2>";
                        }
                        else
                        {
                            echo "Error BD?";
                        }
                    ?></li>
                    <li><?php 
                        if (isset($dinero_generado)){
                            echo $dinero_generado."<h2>euros generados</h2>";
                        }
                        else
                        {
                            echo "Error BD?";
                        }
                    ?></li>
                </ul>
            </div>
            
        </section>

    </main>   
    <?php require_once "app/views/footer.php"; ?>