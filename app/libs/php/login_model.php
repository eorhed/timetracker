<?php
class Login_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    function identificar($email,$clave)
    {
        try{
            $sql = "SELECT * FROM usuarios WHERE email = '$email'";
            $query =  $this->db->query($sql);

            if ($query->num_rows == 1)
                $fila = $query->fetch_object();
                $salt = 12;
                $hash=hash('sha512', $salt.$clave);
                if($fila->hash==$hash){
                    return $fila;
                }
            else
                return "Usuario no encontrado";
        }
        catch(Exception $e){
            return "Error BD:". $e->getMessage();
        }
    }

    // function getAll()
    // {
    //     $sql = "SELECT * FROM usuarios";
    //     $query =  $this->db->query($sql);
    //         $results = array();
    //         while ($row = $query->fetch_object()){
    //             $results[] = $row;
    //         }
            
    //         $query->close();
			
	// 		if ($results) {
	// 			$response['success'] = $results;
	// 			return $response;
	// 		} else {
	// 			$response['error'] = "Usuario no encontrado";
	// 			return $response;
	// 		}
    // }
}