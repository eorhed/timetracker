<?php
/*
*   Clase encargada de realizar gestión de la sesión en PHP
*	Permite iniciar la sesión, destruirla, decidir que hacer tanto si existe una sesión abierta como no,
*   comprobar si el usuario está logueado o si es administrador
*/
class Session
{
	/*
	*   Función que inicializa la sesión en PHP
	*/
	public static function init()
	{
		session_start();
	}

	/*
	*   Función que dado un indice y un valor
	*   almacena en la variable global S_SESSION el valor en el indice pasado como parametro
	*/
	public static function set($key,$value)
	{
		$_SESSION[$key] = $value;
	}

	/*
	*   Función que dado un indice pasado como parametro
	*	devuelve el valor del array $_SESSION asociado a ese indice de array
	*/
	public static function get($key)
	{
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
	}

	/*
	*   Función que destruye la sesión PHP existente y borra las cookies creadas
	*/
	public static function destroy()
	{
		unset($_SESSION);	// borramos todas las variables $_SESSION
		session_destroy();	// destruimos la sesión

		setcookie("TT_user", $_COOKIE["TT_user"], time() - 1);
		setcookie("TT_token", $_COOKIE["TT_token"], time() - 1);
	}

	/*
	*   Función que gestiona la sesión existente
	*	Si ha pasado más de 1 hora sin regenerar la sesión (recargar la página) la sesión se destruye
	*   Si no ha pasado más de 1 hora y se ha recargado la página se regenera la sesió actual
	*	Si no hay sesión pero se encuentra la cookie del usuario almacenada en el navegador, se crea la sesión manualmente
	*	Si no, el usuario no está logueado y se devuelve false, lo que conllevará a la correspondiente redirección a la página de login
	*/
	public static function manageSession() 
	{
		if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {			//usuario logueado con la sesion expirada
			// última peticion hace mas de 60 minutos
			Session::destroy();
			return false;
		} elseif (!empty($_SESSION['last_activity'])) {													//usuario logueado con la sesion sin expirar
			// última peticion hace menos de 60 minutos
			$_SESSION['last_activity'] = time(); // actualizar última actividad
			if (time() - $_SESSION['created'] > 3600) {
				// sesión empezada hace 60 minutos
				session_regenerate_id(true);    // Regeneramos la sesión actual e invalidamos la anterior
				$_SESSION['created'] = time();  // Actualizar la fecha de creación de la última ID de la sesion
			}
			return true;
		} else if (isset($_COOKIE["TT_token"]) && (isset($_COOKIE["TT_user"])))
		{
			$idusuario = $_COOKIE["TT_user"];
			$token = $_COOKIE["TT_token"];

			require_once "app/libs/php/login_model.php";
			$db = new Login_Model("user");
			$usuario = $db->getUsuarioSiTokenValido($idusuario, $token);

			if ($usuario)
			{
				// Regeneramos la sesión manualmente
				Session::set("logueado", true);
				Session::set("idusuario", $usuario->idusuario);
				Session::set("usuario", $usuario->usuario);
                Session::set("email", $usuario->email);
				Session::set("foto", $usuario->foto);
                Session::set("tipo_usuario", $usuario->tipo_usuario);
				Session::set("created", time());
				Session::set("last_activity", time());
				
				return true;
			}
		} else																					//usuario no logueado
			return false;
		

	}

	/*
	*   Función que comprueba si el usuario está logueado, es decir si existen en la variable $_SESSION sus datos
	*/
	static function isLogged() 
	{
		if ($_SESSION["logueado"] = true && !empty($_SESSION['idusuario']) && !empty($_SESSION['usuario']) && !empty($_SESSION['email'])) 
			return true;
		else 
			return false;
	}

	/*
	*   Función que comprueba si el usuario es administrador del sistema
	*/
	static function isAdmin(){
		return $_SESSION["tipo_usuario"] == "Admin";
	}
}
?>