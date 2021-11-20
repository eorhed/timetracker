<?php
require_once "model.php";
class Login_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

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

    function getAll()
    {
        try{
            $sql = "SELECT * FROM usuarios";
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