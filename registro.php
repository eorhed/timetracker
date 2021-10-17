<?php
    require_once "app/views/header.php"; 
    if (Session::manageSession())
        header("Location: dashboard.php")
?>    
    
z
    <?php
		require_once "app/libs/php/model.php";
        require_once "app/libs/php/usuarios_model.php";
        require_once "app/libs/php/login_model.php";
        if (!empty($_POST))
        {
            $email=$_POST["email"];
            $usuario=$_POST["usuario"];
            $password=$_POST["password"];

            // $salt=uniqid(mt_rand(), true);
            $salt = 12;
            $hash=hash('sha512', $salt.$password);

            $db = new Usuarios_Model("anonymous");
            

            if($db->existeEmail($email)){
                echo '<script language="javascript">';
                echo 'alert("El email esta en uso")';
                echo '</script>';
            }else{

                $idusuario = $db->insert($email,$usuario,$hash);
                echo $idusuario;

                //header("Location: login.php");

                require_once "app/libs/php/model.php";
                require_once "app/libs/php/login_model.php";
                $db = new Login_Model("anonymous");
                $usuario = $db->identificar($email,$password);

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

                        echo '<script language="javascript">';
                        echo 'alert("Datos correctamente introducidos. Se iniciará automaticamente su sesión.")';
                        echo '</script>';

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
        <section id="registro">
            <div class="container">
                    <div class="form-registro">
                        <form method="POST" action="registro.php" name="formulario">
                            <h2>P&aacutegina de Registro</h2>
                            <ul>
                                <li>
                                    <input type="email" id="email" name="email" class="form-input-text" placeholder="email">
                                </li>
                                <li>
                                    <input type="text"  id="usuario" name="usuario" class="form-input-text" placeholder="Usuario">
                                </li>
                                <li>
                                    <input type="password" id="password" name="password" class="form-input-text" placeholder="Clave">
                                </li>
                                <li><button type="button" class="boton btn-small bg-primario blanco" onclick="comprobarDatos()">Registrarse</button></li>
                            </ul>
                        </div>
                    </div>
                </form>
        </section>
    </main>
    
    <script>
		function comprobarDatos(){
            var pEmail = document.formulario.email.value;
            var pPassword = document.formulario.password.value;
            var pUsuario = document.formulario.usuario.value;

			if(comprobarEmail(pEmail) && comprobarContra(pPassword) && comprobarUsuario(pUsuario)){
				//window.alert("Datos correctamente introducidos");
				document.formulario.submit();
			}
		}
		function comprobarUsuario(pUsuario){
			//Comprobación nombre correcto
			if(!(/[A-Za-z0-9]{1,20}$/).test(pUsuario)){
				window.alert("Nombre introducido incorrectamente.");
				return false;
			}else{
				return true;
			}
		}
		function comprobarEmail(pEmail){
			//Comprobación email correcto
			if(!(/^[a-zA-Z0-9._]{2,}@[a-z]{2,}[.][a-z]{1,}$/.test(pEmail)) ){
				window.alert("Email introducido incorrectamente.");
				return false;
			}else{
				return true;
			}	
		}
		function comprobarContra(pContra){
			//Comprobación de contraseña
			if(!(/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}/).test(pContra)){
				window.alert("La contraseña debe contenter al menos 8 caracteres, una letra mayúscula, una minúscula y un caracter especial (*-+/)");
				return false;
			}else{
				return true;
			}	
		}
	</script>	
    <?php require_once "app/views/footer.php"; ?>