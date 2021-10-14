<?php

class Session
{
	public static function init()
	{
		session_start();
	}

	public static function set($key,$value)
	{
		$_SESSION[$key] = $value;
	}

	public static function get($key)
	{
		if (isset($_SESSION[$key]))
			return $_SESSION[$key];
	}

	public static function destroy()
	{
		unset($_SESSION);	// borramos todas las variables $_SESSION
		session_destroy();	// destruimos la sesión
	}

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
		} else  																						//usuario no logueado
			return false;
		

	}

	static function isLogged() 
	{
		if ($_SESSION["logueado"] = true && !empty($_SESSION['idusuario']) && !empty($_SESSION['usuario']) && !empty($_SESSION['email'])) 
		{
			return true;
			// // Leemos los datos para conectar a BD
			// $config = parse_ini_file("application/config/database.ini.php"); 
			// $host = $config["db_driver"].":host=".$config["db_host"].";dbname=".$config["db_name"];
			
			// // Conectamos a la BD usando PDO
			// try
			// {
			// 	$db = new PDO($host,$config["db_anom_user"],$config["db_anom_password"]);  
				
			// 	// Comprobamos que el usuario no se ha borrado durante la sesión
			// 	$sql = "SELECT * FROM usuarios WHERE usuario = '$_SESSION[usuario]'";
			// 	$stmt = $db->prepare($sql);
			// 	$stmt->execute();

			// 	$result = $stmt->fetchAll();

			// 	if ($result) 
			// 		return true;	// no se ha borrado
			// 	else 
			// 	{
			// 		//se ha borrado
			// 		session_unset();     // borramos todas las variables $_SESSION
			// 		session_destroy();   // destruimos la sesión
			// 		return false;
			// 	} 
			// }
			// catch (PDOException $pdoEx)
			// {
			// 	echo "Database Error .. Details :<br /> {$pdoEx->getMessage()}";
			// }
		}
		else 
			return false;
	}

	static function isAdmin(){
		return $_SESSION["tipo_usuario"] == "Admin";
	}
}
?>