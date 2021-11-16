<?php
require_once "model.php";
class Tareas_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    function getOne($condiciones=NULL)
    {

	    	$sql = "SELECT * FROM tareas";

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
        $sql = "SELECT * FROM tareas";
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
				$response['error'] = "No hay tareas";
				return $response;
			}
    }

    function insertarTareaBD($tarea){
        
        try
        {
            $fecha_actual = date("Y-m-d");

            $sql = "INSERT INTO tareas (idactividad,nombre,descripcion) VALUES ('{$tarea["idactividad"]}', '{$tarea["nombre"]}', '{$tarea["descripcion"]}')";
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

    function getTareasUsuario($idusuario)
    {
        try
        {
            $sql = "SELECT * FROM actividades AS A INNER JOIN tareas AS T ON A.idactividad = T.idactividad  WHERE A.idusuario = '{$idusuario}' ORDER BY A.idactividad ASC;";
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
            echo "<div class='info-error'>ERROR:".$e->getMessage()."</div>";
            die();
        }
	    
    }

    function getTareasActividad($idactividad)
    {
        try 
        {
            $sql = "SELECT * FROM tareas WHERE idactividad = '$idactividad'";
            $query = $this->db->query($sql);
        
            $tareas = array();
                while ($tarea = $query->fetch_object())
                    $tareas[] = $tarea;
        
            if (count($tareas))
                return $tareas;
            else
                return 0;
        }
        catch (Exception $e) {
            echo "<div class='info-error'>ERROR:".$e->getMessage()."</div>";
            die();
        }
    }


    function validarTarea($tarea)
    {
        $tarea['idactividad'] = isset($tarea['idactividad']) ? mysqli::real_escape_string($tarea['idactividad']) : false;
        $tarea['nombre'] = isset($tarea['nombre']) ? mysqli::real_escape_string($tarea['nombre']) : false;
        $tarea['descripcion'] = isset($tarea['descripcion']) ? mysqli::real_escape_string($tarea['descripcion']) : false;

            // Validación
            $respuesta['errores'] = array();

            if (empty($tarea['idactividad']))
                $respuesta['errores']['tarea'] = 'Campo actividad no puede estar vacío';

            if (empty($tarea['nombre']))
                $respuesta['errores']['nombre'] = 'Campo nombre tarea no puede estar vacío';

            if(strlen($tarea['nombre']) > 100)
                $respuesta['errores']['nombre'] = 'Campo descripci&oacute;n excede los 100 caracteres';

            if(strlen($tarea['descripcion']) > 300)
                $respuesta['errores']['descripcion'] = 'Campo descripci&oacute;n excede los 300 caracteres';
            
            
            if(count($respuesta['errores']) == 0){
        
                return $respuesta['success'] = true;
        
            }else{
                return $respuesta['errores'];
            }
    }

    // function update($tarea)
    // {
    //     try {
    //         $sql = "UPDATE tareas SET idactividad = '{$tarea['idactividadd']}', nombre = '{$tarea['nombre']}', descripcion = '{$tarea['descripcion']}' WHERE idtarea = '{$tarea['idtarea']}'";
    //         $result =  $this->db->query($sql);

	// 			if ($result) {
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

    // function delete($tarea)
    // {
    //     try
    //     {
    //         $sql = "DELETE FROM tarea WHERE idtarea = '{$tarea['idtarea']}'";
    //         $query = $this->db->query($sql);

    //         if ($query)
    //             return "Ha borrado bien la tarea";

    //     } catch(Exception $e){
    //         echo 'ERROR:'.$e->getMessage()."<br>";
    //         die();
    //     }
    // }
}