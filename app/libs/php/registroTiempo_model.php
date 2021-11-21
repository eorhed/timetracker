<?php
require_once "model.php";

/*
*   Clase encargada de gestionar todas las operaciones relacionadas con los registros de seguimiento de tiempo de la pantalla Trackear
*/
class RegistroTiempo_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    /*
    *   Función que dado un array con las condiciones devuelve el registro de seguimiento de la tabla registros
    */
    function getOne($condiciones=NULL)
    {

	    	$sql = "SELECT * FROM registros";

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
				$response['error'] = "Registro de seguimiento no encontrado";
				return $response;
			}
    }

    /*
    *   Función que devuelve todos los registros de seguimiento almacenados en la BD
    */
    function getAll()
    {
        $sql = "SELECT * FROM registros";
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
				$response['error'] = "No hay registros de seguimiento";
				return $response;
			}
    }

    /*
    *   Función que dado un array con los datos del registro de seguimiento inserta el registro (trackeo de tiempo de la actividad-tarea) 
    *   en la tabla registros de la BD
    */
    function insertarRegistroSeguimientoBD($registro){
        
        // $registro["fecha_inicio"]   18/11/2021 01:12:02
        $fecha_inicio = substr($registro["fecha_inicio"],6,4)."-".substr($registro["fecha_inicio"],3,2)."-".substr($registro["fecha_inicio"],0,2) . " " . substr($registro["fecha_inicio"],11,8);
        $fecha_actual = date("Y-m-d H:i:s");

        try
        {
            $sql = "INSERT INTO registros (idtarea,fecha_inicio,fecha_fin,duracion,comentarios) VALUES ('{$registro["idtarea"]}', '{$fecha_inicio}', '{$fecha_actual}', '{$registro["duracion"]}', '{$registro["comentarios"]}')";
            $result =  $this->db->query($sql);

            
            $id = $this->db->insert_id;

            return "Ha insertado bien";
        }
        catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            
            return $e->getMessage();

            // //Manejo SIN excepciones (normal) de errores mysqli
            // if ($this->db->error)                        
            //     echo $this->db->error."<br>";
        }
    }

    /*
    *   Función que dado un id de usuario y un numero de registros
    *   devuelve los ultimos registros de seguimiento del usuario, tantos como se indique en numRegistros
    */
    function getRegistrosSeguimientoUsuario($idusuario, $numRegistros = null)
    {
        $sql =  "SELECT A.actividad, T.nombre, R.fecha_inicio, R.fecha_fin, R.duracion FROM actividades AS A INNER JOIN tareas AS T INNER JOIN registros AS R ON A.idactividad = T.idactividad AND T.idtarea = R.idtarea WHERE A.idusuario = '$idusuario' ORDER BY R.idregistro DESC";
        
        if (isset($numRegistros))
            $sql .= " LIMIT $numRegistros";
        
        $query =  $this->db->query($sql);
            $results = array();
            while ($row = $query->fetch_object()){
                $results[] = $row;
            }
            
            $query->close();
			
			if ($results)
				return $results;
			else 
				return NULL;
	}
}