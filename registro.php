<?php
    require_once "app/views/header.php";

    // Controla la sesión de usuario. Si ya está logueado o tiene la cookie le devuelve a dashboard
    if (Session::manageSession())
        header("Location: dashboard.php")
?>

<?php
		require_once "app/libs/php/model.php";
        require_once "app/libs/php/usuarios_model.php";
        require_once "app/libs/php/login_model.php";

        if (!empty($_POST))
        {
            $email = $_POST["email"];
            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            // $salt=uniqid(mt_rand(), true);
            $salt = 12;
            $hash = hash('sha512', $salt.$password);

            $db = new Usuarios_Model("anonymous");
            

            if($db->existeEmail($email)){
                echo '<script language="javascript">';
                echo 'alert("El email esta en uso")';
                echo '</script>';
            }else{

                // Insertamos el usuario en la BD
                $idusuario = $db->insert($email, $usuario, $hash);
                echo $idusuario;


                require_once "app/libs/php/model.php";
                require_once "app/libs/php/login_model.php";
                $db = new Login_Model("anonymous");
                $usuario = $db->identificar($email, $password);

                if (isset($usuario))
                {
                    if (is_object($usuario))
                    {
                        Session::set("logueado", true);
                        Session::set("idusuario", $usuario->idusuario);
                        Session::set("usuario", $usuario->usuario);
                        Session::set("email", $usuario->email);
                        Session::set("foto", $usuario->foto);
                        Session::set("tipo_usuario", $usuario->tipo_usuario);
                        Session::set("created", time());
                        Session::set("last_activity", time());

                        // Generamos también el token y lo insertamos en la BD
                        $idusuario = $usuario->idusuario;
                        $token = mt_rand(1000000,999999999);

                        $resultado = $db->insertarToken($idusuario, $token);

                        if ($resultado == true){
                            setcookie("TT_user", $idusuario, time() + 3600*24*30);  // Generamos la cookie para que dure 30 días
                            setcookie("TT_token", $token, time() + 3600*24*30);        // Generamos la cookie para que dure 30 días
                        }
                        else
                            header("Location: login.php");  // La insercion del token en BD ha fallado, redirigimos a login para que loguee manualmente

                        echo '<script language="javascript">';
                        echo 'alert("Datos correctamente introducidos. Se iniciará automaticamente su sesión.")';
                        echo '</script>';

                        header("Location: dashboard.php");
                    }
                    else 
                        echo "<div class='info-error'>Fallo en la BD</div>"; //INDICAR FALLO EN EL PROCESO DE LOGUEO
                }
                else
                    Session::destroy();
            }
        }
        
    ?>

<main>
    <section id="registro">
        <div class="container">
            
            <div class="form-registro">
                <form method="POST" action="registro.php" name="formulario">
                    <ul>
                        <li><h2>Registro de usuario</h2></li>
                        <li>
                            <label for="usuario">Usuario</label>
                            <input type="text" id="usuario" name="usuario" class="form-input-text"
                                placeholder="Usuario">
                        </li>
                        <li>
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-input-text" placeholder="Email">
                        </li>
                        <li>
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-input-text"
                                placeholder="Clave">
                        </li>
                        <li class="paddingT50">Al registrarte entiendes y aceptas las <a href="#"
                                onclick="mostrarCondicionesUso();">condiciones de uso</a></li>
                        <li class="centrado paddingT25"><button type="button" class="boton btn-grande bg-primario c_blanco"
                                onclick="comprobarDatos()">Registrarse</button></li>
                    </ul>
            </div>
        </div>
        </form>
        <div id="info-condiciones-uso">
            <div class="boton-cerrar-cuadro" onclick="cerrarCondicionesUso();"><img src="app/assets/img/x-circle-fill.svg" alt="Icono cerrar ventana"></div>
                <div class='contenido-condiciones-uso'>
                    <h1>Acuerdo final de consentimiento del usuario</h1>

                    <p>Esta aplicación Timetracker está sujeta a la ley RGPD(Regulación General de Protección de Datos)
                        publicada en 2016 y vigente desde 2018.</p>

                    <h2>El usuario deberá leer, entender y aceptar las siguientes directivas:</h2>


                    <h2>Protección de los datos del usuario</h2>

                    <p>Los datos personales tales como la contraseña serán cifrados mediante procedimientos criptográficos, a
                        fin de que en caso de sufrir un ataque tecnológico, queden totalmente ilegibles ante una
                        sustracción.<br />
                    </p>


                    <h2>Privacidad</h2>

                    <ol>
                        <li>Los datos personales del usuario no podrán ser accedidos desde otro usuario, perfil, o aplicación ajena
                        al entorno personal dentro de esta aplicación web.</li>

                        <li>Sólo podrán ser consultados por el administrador de esta aplicación a efectos de garantizar el debido
                        uso de la aplicación, respeto a los valores morales, legales y de cualquier índole.</li>

                        <li>Los datos que usted introduzca a través de los formularios, serán transmitidos de forma segura e
                        invisible(mediante paso de parámetros POST) entre las distintas páginas dentro de esta aplicación.</li>
                    </p>


                    <h2>Rastreo de actividad</h2>

                    <p>El usuario da su consentimiento para que la aplicación guarde en la máquina del cliente(usted), una Cookie*,
                    mediante la cual se almacene su contraseña, con el objetivo que no tenga que estar logueandose cada vez que
                    entre al sistema.

                    La aplicación no realiza rastreo, análisis, almacenamiento ni comercialización alguna de las actividades del
                    usuario dentro del ámbito de esta web.

                    Los responsables del sistema tienen derecho a disponer, utilizar y almacenar todos los datos que el usuario
                    introduzca, a fin de que sirvan como entrada para las funcionalidades de la aplicación de forma exclusiva.</p>


                    <h2>Derecho al olvido</h2>

                    <p>El usuario tiene derecho a darse de baja de la aplicación, y cuando esto suceda, todos sus datos, trazas de
                    actividad, imágenes, almacenados en esta aplicación Web, queden debidamente borrados a fin de garantizar que
                    otras personas ajenas a este entorno puedan utilizarlos.</p>


                    <h2>Portabilidad</h2>

                    <p>El usuario tiene derecho a realizar una petición formal al encargado de esta aplicación, que le permita
                    saber de forma clara y fácil, qué datos sobre su persona se están guardando en dicha aplicación.</p>



                    <h2>Esta aplicación <b>Timetracker</b> está sujeta a la ley <b>LSSI 34/2002 (Ley de Servicios de la Sociedad de la
                    Información)</b> publicada en 2002 y vigente en la actualidad.</h2>

                    El usuario deberá leer, entender y aceptar las siguientes directivas:


                    <h2>Uso de los datos/comercialización</h2>

                    El usuario entiende y acepta que sus datos no serán objetivo de ningún tipo de comercialización, negocio, ni
                    utilización ajena fuera del ámbito de esta aplicación Web.


                    <h2>Actividad económica</h2>

                    Esta aplicación Timetracker, no desarrolla actividad económica alguna, no tiene como objetivo generar
                    beneficios. Únicamente persigue fines educativos y lúdicos.

                    Cookie*: Son archivos que se almacenan en el directorio del navegador en la máquina del cliente(usted), que
                    guardan cadenas de texto, como por ejemplo, el usuario y la contraseña.
                </div>
        </div>
    </section>
</main>
<?php require_once "app/views/footer.php"; ?>