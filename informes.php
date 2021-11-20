<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
// error_reporting(0);
?>
<?php require_once "app/views/header.php"; ?>
<?php 
    if (!Session::manageSession() || !Session::isLogged())
        header("Location: login.php");
        
        require_once "app/libs/php/model.php";
        require_once "app/libs/php/dashboard_model.php"; 
?>

<main>
    <section id="informes">
        <div class="container">
            <h1>Informes</h1>
            <div class="cabecera">Lista de informes</div>
            <div class="lista-informes">
                <ul>
                <?php
                    require_once "app/libs/php/informes_model.php";
                    $db = new Informes_Model("user");
                    $actividades = $db->getActividadesUsuarioConRegistro(Session::get('idusuario'));

                    if (!empty($actividades))
                    {
                        foreach ($actividades as $actividad) {
                            echo "<li><a href='ver.php?id={$actividad->idactividad}'><div class='actividad'>$actividad->actividad</div><div class='cabecera-info'><span>$actividad->duracion_total</span></div></a></li>";
                        }
                    }
                    else
                        echo "<li>No existe ningún informe de seguimiento todavía</li>";
                ?>
                    <div class="num-resultados floatR"><?php echo (isset($actividades) && is_array($actividades)) ? count($actividades) : 0 ?> resultados</div>
                </ul>
            </div>
        </div>
    </section>
</main>
<?php require_once "app/views/footer.php"; ?>