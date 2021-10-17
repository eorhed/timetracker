<?php
class Usuarios_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    function getOne($condiciones=NULL)
    {
            //$usuario = Session::get("usuario");

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

    /* function update($usuario)
    {
        try {
            $sql = "UPDATE usuarios SET usuario = '{$usuario['usuario']}', clave = '{$usuario['clave']}', email = '{$usuario['email']}' WHERE usuario = '{$usuario['usuario']}'";
            $result =  $this->db->query($sql);

				if ($result) {
                    //Warning si falla la subida de la foto, falta implementar
					if (isset($warning))
						$response['success'] = msg_success_editUser_function . "<br />" . $warning;
					else
						$response['success'] = msg_success_editUser_function;
					return $response;
				} else {
					$response['error'] = msg_error_editUser_function;
					return $response;
				}

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    } */

    function delete($usuario)
    {
        try
        {
            $sql = "DELETE FROM usuarios WHERE usuario = '{$usuario['usuario']}'";
            $query = $this->db->query($sql);

            if ($query)
                return "Ha borrado bien el usuario";

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }
    
}