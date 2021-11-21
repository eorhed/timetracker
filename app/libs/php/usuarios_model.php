<?php
require_once "model.php";
class Usuarios_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    function getOne($condiciones=NULL)
    {
	    	$sql = "SELECT * FROM usuarios";

            if (is_array($condiciones) && count($condiciones))
            {
                $sql .= " WHERE ";
                
                $cont = 1;
                foreach ($condiciones as $condicion => $valor)
                {
                    if ($cont == 1)
                        $sql .= $condicion . " = '" . $valor . "'";
                    else
                        $sql .= "," . $condicion . " = '" . $valor . "'";
                    
                    $cont++;
                }
            }

			$query =  $this->db->query($sql);
            $results = array();

            if ($query->num_rows == 1)
                return $query->fetch_object();
            else
				return NULL;
    }

    function getAll()
    {
        $sql = "SELECT * FROM usuarios";
        $query =  $this->db->query($sql);
            $results = array();
            while ($row = $query->fetch_object()){
                $results[] = $row;
            }
            
            $query->close();
			
			if ($results) {
				$response['success'] = $results;
				return $response;
			} else {
				$response['error'] = "Usuario no encontrado";
				return $response;
			}
    }

    function insert($email,$usuario,$hash){
        
        try
        {
            echo $hash."<br>";
            echo "longitud hash: ".strlen($hash);
            $fecha_actual = date("Y-m-d");

            $sql = "INSERT INTO usuarios (usuario,hash,email,fecha_registro) VALUES ('{$usuario}', '{$hash}','{$email}','{$fecha_actual}')";
            $result =  $this->db->query($sql);

            $id = $this->db->insert_id;
            return $id;
        }
        catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    function existeEmail($email){
        try{
            $sql = "SELECT * FROM usuarios WHERE email = '$email'";
            $query =  $this->db->query($sql);

            if ($query->num_rows >= 1){
                return true;
            }else{
                return false;
            }
        }
        catch(Exception $e){
            return "Error BD:". $e->getMessage();
        }
    }

    
    function darDeBajaUsuario($idusuario, $token)
    {
        try
        {
            $sql = "DELETE FROM usuarios WHERE idusuario = '$idusuario' and token = '$token'";
            $query = $this->db->query($sql);

            if ($query)
                return true;
            else
                return null;

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    function getUsuarios()
    {
        try
        {
            $sql = "SELECT idusuario, email, usuario  FROM usuarios WHERE tipo_usuario = 'usuario'";
            $query = $this->db->query($sql);

            $usuarios = array();
            while ($fila = $query->fetch_object()){
                $usuarios[] = $fila;
            }
            
            $query->close();
			
			if ($usuarios) {
				return $usuarios;
			} else {
				return 0;
			}

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    function borrarUsuario($idusuario)
    {
        try {
            // Limpiamos el parametro pasado a la función
            $idusuario = $this->db->real_escape_string($idusuario);

            $sql = "DELETE FROM usuarios WHERE idusuario = '$idusuario'";
            $result =  $this->db->query($sql);
				
            if ($result) 
                return true;
			else 
				return false;
				
        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }
}