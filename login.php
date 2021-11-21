<?php
    require_once "app/views/header.php";
    
    // Controla la sesión de usuario. Si ya está logueado o tiene cookie le devuelve al dashboard
    if (Session::manageSession())
        header("Location: dashboard.php")
?>    
    
    <main>
        <section id="login">

            <?php
            if (isset($_POST["email"]))
            {
                // Cogemos y limpiamos los datos del formulario
                        $usuario["email"] = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
                        $usuario["password"] = trim(filter_var($_POST["password"], FILTER_SANITIZE_STRING));

                if (!empty($usuario["email"]) && !empty($usuario["password"]))
                {
                    // Primero comprobamos que dicho usuario existe en la BD
                    require_once "app/libs/php/login_model.php";
                    $db = new Login_Model("anonymous");
                    $usuario = $db->identificar($usuario["email"],$usuario["password"]);

                    if (isset($usuario))
                    {
                        // echo "<pre>";
                        // var_dump($usuario);
                        // echo "</pre>";
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

                            $db = new Login_Model("user");
                            $resultado = $db->insertarToken($idusuario, $token);

                            if ($resultado == true){
                                setcookie("TT_user", $idusuario, time() + 3600*24*30);  // Generamos la cookie para que dure 30 días
                                setcookie("TT_token", $token, time() + 3600*24*30);        // Generamos la cookie para que dure 30 días
                            }
                            else
                                echo "<div class='info-error'>No se ha podido insertar el token</div>";
                            

                            header("Location: dashboard.php");
                        }
                        else 
                            echo "<div class='info-error'>Fallo en la BD</div>"; //Indicamos el fallo en el proceso de logueo
                    }
                    else
                    {
                        // Session::destroy();
                        
                        // TODO FALTA MOSTRAR EL ERROR
                        echo "<div class='info-error'>El usuario ingresado no existe o credenciales incorrectas</div>";
                    }
                }
            }
            ?>

            <div class="container">
                    <div class="form-login">
                        <form method="POST" action="login.php">
                            <h2>Iniciar sesión</h2>
                            <ul>
                                <li>
                                    <!-- <label for="email">Usuario</label> -->
                                    <input type="email" id="email" name="email" class="form-input-text" placeholder="Email">
                                </li>
                                <li>
                                    <!-- <label for="password">Clave</label> -->
                                    <input type="password" id="password" name="password" class="form-input-text" placeholder="Clave">
                                </li>
                                <li class="centrado"><button type="submit" class="boton btn-grande bg-primario c_blanco">Iniciar sesión</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
        </section>
    </main>

    <?php require_once "app/views/footer.php"; ?>