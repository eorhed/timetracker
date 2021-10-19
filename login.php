<?php
    require_once "app/views/header.php"; 
    if (Session::manageSession())
        header("Location: dashboard.php")
?>    
    

    <?php
    if (isset($_POST["email"]))
    {
        // Cogemos y limpiamos los datos del formulario
	    		$usuario["email"] = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
	    		$usuario["password"] = trim(filter_var($_POST["password"], FILTER_SANITIZE_STRING));

		if (!empty($usuario["email"]) && !empty($usuario["password"]))
		{
            // Primero comprobamos que dicho usuario existe en la BD
            require_once "app/libs/php/model.php";
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

					header("Location: dashboard.php");
				}
				else 
					echo "Fallo en la BD"; //INDICAR FALLO EN EL PROCESO DE LOGUEO
			}
			else
			{
				Session::destroy();
                // TODO FALTA MOSTRAR EL ERROR
			}
		}
    }
    ?>


    <main>
        <section id="login">
            <!-- <div class="bloque_I">
                <div class="container"><div><h1>Timetracker</h1></div></div>
            </div>
            <div class="bloque_D">
                
            </div> -->
            <div class="container">
                    <div class="form-login">
                        <form method="POST" action="login.php">
                            <h2>Iniciar sesión</h2>
                            <ul>
                                <li>
                                    <!-- <label for="email">Usuario</label> -->
                                    <input type="email" id="email" name="email" class="form-input-text" placeholder="email">
                                </li>
                                <li>
                                    <!-- <label for="password">Clave</label> -->
                                    <input type="password" id="password" name="password" class="form-input-text" placeholder="Clave">
                                </li>
                                <li><button type="submit" class="boton btn-small bg-primario blanco">Iniciar sesión</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
        </section>
    </main>

    <?php require_once "app/views/footer.php"; ?>