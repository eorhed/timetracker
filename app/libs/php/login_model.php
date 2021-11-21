<?php
require_once "model.php";

/*
*   Función que dado un id de usuario devuelve sus actividades
*/
class Login_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    /*
    *   Función que dado un email y una clave busca el usuario
    *   Si existe, quiere decir que el usuario se ha identificado correctamente
    *   Si no se devuelve null y el controlador le mostrará el error
    */
    function identificar($email, $clave)
    {
        try{
            $email = $this->db->real_escape_string($email);
            $clave = $this->db->real_escape_string($clave);

            $sql = "SELECT * FROM usuarios WHERE email = '$email'";
            $query =  $this->db->query($sql);

            if ($query->num_rows == 1)
            {
                $fila = $query->fetch_object();
                $salt = 12;
                $hash = hash('sha512', $salt.$clave);
                
                if ($fila->hash == $hash)
                    return $fila;
            }   
            else
                return null;    // Usuario no encontrado
        }
        catch(Exception $e){
            return "Error BD:". $e->getMessage();
        }
    }

    /*
    *   Función que dado un id de usuario y un token inserta el token de la cookie en este usuario en la BD
    */
    function insertarToken($idusuario, $token)
    {
        try {
            $sql = "UPDATE usuarios SET token = '$token' WHERE idusuario = '$idusuario'";
            $result =  $this->db->query($sql);
			
            return $result;
        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    /*
    *   Función que dado un id de usuario y un token devuelve el usuario con ese token y esa id
    */
    function getUsuarioSiTokenValido($idusuario, $token)
    {
        try {
            $sql = "SELECT * FROM usuarios WHERE idusuario = '$idusuario' AND token = '$token'";
            $query =  $this->db->query($sql);
            $fila = $query->fetch_object();

            if ($query->num_rows == 1)
                return $fila;
            else
                return null;    // Usuario no encontrado

        } catch(Exception $e){
                echo 'ERROR:'.$e->getMessage()."<br>";
                die();
        }
    }

    /*
    *   Función que devuelve todos los usuarios de la tabla usuarios
    */
    function getAll()
    {
        try{
            $sql = "SELECT * FROM usuarios WHERE tipo_usuario = 'usuario'";
            $query =  $this->db->query($sql);
        
            $results = array();
            while ($row = $query->fetch_object())
                $results[] = $row;
            
                
            $query->close();
                
            if ($results) {
                $response['success'] = $results;
                return $response;
            } else {
                $response['error'] = "Usuario no encontrado";
                return $response;
            }
        }
        catch(Exception $e){
            return "Error BD:". $e->getMessage();
        }
    }
}