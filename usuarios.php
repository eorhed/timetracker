<?php require_once "app/views/header.php"; ?>
<?php 

    // Controla la sesión de usuario. Si no está logueado o no tiene cookie o NO ES ADMIN le devuelve a login
    if (!Session::manageSession() || !Session::isLogged() || !Session::isAdmin())
            header("Location: login.php");

    
    if ($_POST)
    {
        // Antes de borrar limpiamos la id del usuario
        
        $idusuario = filter_input(INPUT_POST, "idusuario", FILTER_SANITIZE_NUMBER_INT);
        if (isset($idusuario))
        {
            // Borramos el usuario pasado por POST
            require_once "app/libs/php/usuarios_model.php";
            $db = new Usuarios_Model("admin");
            
            if ($db->borrarUsuario($idusuario))
                echo "<div class='info-success'>El usuario ha sido borrado correctamente</div>";
            else
                echo "<div class='info-error'>Se ha producido un error al borrar el usuario. Int&eacute;ntalo m&aacute;s tarde</div>";
        }
    }

?>
    
    <main>
        <section id="usuarios">
            <div class="container">
                <h1>Usuarios</h1>
                <form name="formulario" id="formulario" method="POST">
                    <div class="lista-usuarios">
                        <h2>Lista de usuarios</h2>
                        <ul>
                        <?php 
                            require_once "app/libs/php/usuarios_model.php";
                            $db = new Usuarios_Model("admin");
                            $usuarios = $db->getUsuarios();

                            if (isset($usuarios) && is_array($usuarios) && count($usuarios) > 0)
                            {
                                foreach ($usuarios as $usuario)
                                    echo "<li><div class='nombre-usuario'>$usuario->usuario</div><div class='email-usuario'>$usuario->email</div><div class='accion-borrar'><button type='button' class='boton btn-pequeno bg-danger c_blanco' onclick='javascript: return borrarUsuario($usuario->idusuario);'>Borrar</button></div></li>";
                            }
                            else{
                                echo "<li>No hay usuario registrados en el sistema</li>";
                            }
                        ?>
                        </ul>
                    </div>
                    <input type="hidden" id="idusuario" name="idusuario" value=>
                </form>
            </div>
        </section>
    </main>

<?php require_once "app/views/footer.php"; ?>