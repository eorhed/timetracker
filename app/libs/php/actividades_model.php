<?php
require_once "model.php";
class Actividades_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    function getOne($condiciones=NULL)
    {

	    	$sql = "SELECT * FROM actividades";

            

            if (is_array($condiciones) && count($condiciones))
            {
                $sql .= " WHERE ";
                
                $cont = 1;
                foreach ($condiciones as $condicion => $valor)
                {
                    // Limpiamos los inputs
                    // $this->db->real_escape_string($condicion);
                    // $this->db->real_escape_string($valor);

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
        $sql = "SELECT * FROM actividades";
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
				$response['error'] = "No hay actividades";
				return $response;
			}
    }

    function insertarActividadBD($actividad){
        
        try
        {
            $fecha_actual = date("Y-m-d");

            $sql = "INSERT INTO actividades (idusuario,actividad,cliente,comentarios,fecha_inicio) VALUES ('{$actividad["idusuario"]}', '{$actividad["actividad"]}', '{$actividad["cliente"]}', '{$actividad["comentarios"]}','{$fecha_actual}')";
            $result =  $this->db->query($sql);

            
            $id = $this->db->insert_id;

            return $id;
        }
        catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();

            // //Manejo SIN excepciones (normal) de errores mysqli
            // if ($this->db->error)                        
            //     echo $this->db->error."<br>";
        }
    }

    function getActividadesUsuario($idusuario){
        try{
            $sql = "SELECT * FROM actividades WHERE idusuario = '{$idusuario}' ORDER BY idactividad ASC;";
            $query = $this->db->query($sql);
        
            $actividades = array();
                while ($actividad = $query->fetch_object())
                    $actividades[] = $actividad;
        
            if (count($actividades))
                return $actividades;
            else
                return 0;
        }catch(Exception $e)
        {
            echo "<div class='info-warning'>ERROR:".$e->getMessage()."</div>";
            die();
        }
	    
    }


    function validarActividad($actividad)
    {
        $actividad["actividad"] = isset($actividad['actividad']) ? mysqli::real_escape_string($actividad["actividad"]) : false;
        $actividad["cliente"] = isset($actividad['cliente']) ? mysqli::real_escape_string($actividad["cliente"]) : false;
        $actividad["comentarios"] = isset($actividad['comentarios']) ? mysqli::real_escape_string($actividad["comentarios"]) : false;
        $actividad["precio_x_hora"] = isset($actividad['precio_x_hora']) ? mysqli::real_escape_string($actividad["precio_x_hora"]) : false;
        $actividad["precio_estimado"]  = isset($actividad['precio_estimado']) ? mysqli::real_escape_string($actividad["precio_estimado"]) : false;

            // Validación
            $respuesta['errores'] = array();

            if (empty($actividad["actividad"]))
                $respuesta['errores']['actividad'] = 'Campo actividad no puede estar vacío';

            if (strlen($actividad['actividad']) > 100)
                $respuesta['errores']['actividad'] = 'Campo actividad excede los 100 caracteres';
            
            // if(empty($actividad["cliente"]))
            //     $respuesta['errores']['cliente'] = 'El cliente no es válido';
            
            if(strlen($actividad['comentarios']) > 300)
                $respuesta['errores']['comentarios'] = 'Los comentarios exceden los 300 caracteres';
            
            if(empty($actividad['fechaInicio']))
                $respuesta['errores']['fechaInicio'] = 'Fecha Inicio vacía';
            
            if(empty($actividad['fechaFin']))
                $respuesta['errores']['fechaFin'] = 'Fecha Fin vacía';
            
            if(empty($actividad['precio_x_hora']) || !is_float($actividad['precio_x_hora']))
                $respuesta['errores']['precio_x_hora'] = 'Precio hora vacío o no es un número válido';

            if(empty($actividad['estimado']) || !is_float($actividad['precio_estimado']))
                $respuesta['errores']['precio_estimado'] = 'Precio estimado vacío o no es un número válido';
        
            
            if(count($respuesta['errores']) == 0){
        
                return $respuesta['success'] = true;
        
            }else{
                return $respuesta['errores'];
            }
    }

    // Para la página de Trackear.php
    function getPrimeraActividadConTareasUsuario($idusuario)
    {
        try
        {
            $sql = "SELECT A.idactividad FROM actividades AS A INNER JOIN tareas AS T ON A.idactividad = T.idactividad WHERE idusuario = '$idusuario' LIMIT 1";

            $query =  $this->db->query($sql);
            $results = array();
            
            while ($row = $query->fetch_object())
                $results[] = $row;
                
            $query->close();
                
            if ($results) 
                return $results[0]->idactividad;
            else
                    return 0;
        }
        catch(Exception $e)
        {
            echo "<div class='info-error'>Error:".$e->getMessage()."</div>";
        }
    }

    // function update($usuario)
    // {
    //     try {
    //         $sql = "UPDATE usuarios SET usuario = '{$usuario['usuario']}', clave = '{$usuario['clave']}', email = '{$usuario['email']}' WHERE usuario = '{$usuario['usuario']}'";
    //         $result =  $this->db->query($sql);

	// 			if ($result) {
    //                 //Warning si falla la subida de la foto, falta implementar
	// 				if (isset($warning))
	// 					$response['success'] = msg_success_editUser_function . "<br />" . $warning;
	// 				else
	// 					$response['success'] = msg_success_editUser_function;
	// 				return $response;
	// 			} else {
	// 				$response['error'] = msg_error_editUser_function;
	// 				return $response;
	// 			}

    //     } catch(Exception $e){
    //         echo 'ERROR:'.$e->getMessage()."<br>";
    //         die();
    //     }
    // }

    // function delete($usuario)
    // {
    //     try
    //     {
    //         $sql = "DELETE FROM usuarios WHERE usuario = '{$usuario['usuario']}'";
    //         $query = $this->db->query($sql);

    //         if ($query)
    //             return "Ha borrado bien el usuario";

    //     } catch(Exception $e){
    //         echo 'ERROR:'.$e->getMessage()."<br>";
    //         die();
    //     }
    // }
}