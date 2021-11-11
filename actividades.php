<?php require_once "app/views/header.php"; ?>
<?php require_once "app/config/mensajes.php"; ?>
    
    <main>
        <section id="actividades">
            <div class="container">
                <h1>Actividades</h1>
                    <button class="boton bg-primario btn-pequeno c_blanco negrita floatR">
                        <a href="crear_actividad.php">
                            <img src="app/assets/img/file-earmark-plus2.png" alt="Imagen nuevo archivo">
                            Nueva actividad
                        </a>
                    </button>
                    <div class="clear"></div>

                    <?php 
                        require_once "app/libs/php/actividades_model.php";
                        $db = new Actividades_Model("user");
                        $actividades = $db->getActividadesUsuario(Session::get('idusuario'));

                        
                    ?>
                    <div class="tabla">
                        <table>
                            <thead>
                                <tr>
                                    <td width="20">#</td>
                                    <td>ACTIVIDAD</td>
                                    <td align="center" width="200">FECHA CREACI&Oacute;N</td>
                                    <td align="center" width="200">FECHA FIN</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if (!empty($actividades) && is_integer($actividades) && $actividades == 0)
                                    {
                                        echo "<tr><td colspan='3'><div class='info-warning'>No hay actividades creadas</td></tr>";
                                    }
                                    else{
                                        $cont = 1;
                                        foreach ($actividades as $actividad) {
                                            echo "<tr><td>$cont</td><td>$actividad->actividad</td><td>$actividad->fecha_inicio</td><td>$actividad->fecha_fin</td></tr>";
                                            
                                            $cont++;
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>