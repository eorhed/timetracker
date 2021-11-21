<?php
require_once "model.php";

/*
*   Clase encargada de gestionar las operaciones en la BD relacionadas con la pantalla Tareas y la tabla Tareas
*   (tareas.php, crear_tarea.php, editar_tarea.php)
*/
class Tareas_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    /*
    *   Función que dadas unas condiciones en forma de array devuelve una tarea en concreto según esas condiciones pasadas
    */
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

    /*
    *   Función que devuelve todos las tareas de la tabla Tareas de la BD
    */
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

    /*
    *   Función que dado un array con los datos de la tarea, inserta en la BD dicha tarea
    */
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

    /*
    *   Función que dado un id de usuario pasado como parametro devuelve las tareas de dicho usuario
    */
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

    /*
    *   Función que dada una id de actividad, devuelve las tareas asociadas a esa actividad
    */
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

    /*
    *   Función que valida los campos del formulario de crear/editar tarea
    */
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

    /*
    *   Función que devuelve si esa tarea, pasada su id como parámetro, pertenece a ese usuario pasado también como parametro
    */
    function perteneceTareaAUsuario($idtarea, $idusuario)
    {
        $idtarea = $this->db->real_escape_string($idtarea);
        $idusuario = $this->db->real_escape_string($idusuario);
        
        try 
        {
            $sql = "SELECT * FROM actividades AS A INNER JOIN tareas AS T ON A.idactividad = T.idactividad  WHERE A.idusuario = '{$idusuario}' AND T.idtarea = '{$idtarea}'";
            $query = $this->db->query($sql);
        
            if ($query->num_rows == 1)
                return true;
            else
                return false;
        }
        catch (Exception $e) {
            echo "<div class='info-error'>ERROR:".$e->getMessage()."</div>";
            die();
        }
    }

    /*
    *   Función que dada un id de tarea y un id de usuario, devuelve todos los datos de esa tarea asociada a ese usuario
    */
    function getTareaUsuario($idtarea,$idusuario)
    {
        $idtarea = $this->db->real_escape_string($idtarea);
        $idusuario = $this->db->real_escape_string($idusuario);
        
        try 
        {
            $sql = "SELECT T.* FROM actividades AS A INNER JOIN tareas AS T ON A.idactividad = T.idactividad  WHERE A.idusuario = '{$idusuario}' AND T.idtarea = '{$idtarea}'";
            $query = $this->db->query($sql);
        
            if ($query->num_rows == 1)
                return $query->fetch_object();
            else
                return NULL;
        }
        catch (Exception $e) {
            echo "<div class='info-error'>ERROR:".$e->getMessage()."</div>";
            die();
        }
    }

    /*
    *   Función que con unos datos de la tarea pasados en forma de array $tarea actualiza dicha fila en la tabla tareas de la BD
    */
    function editarTarea($tarea)
    {
        try 
        {
            $idactividad = $this->db->real_escape_string($tarea["idactividad"]);
            $idtarea = $this->db->real_escape_string($tarea["idtarea"]);
            $nombre = $this->db->real_escape_string($tarea["nombre"]);
            $descripcion = $this->db->real_escape_string($tarea["descripcion"]);

            $sql = "UPDATE tareas SET idactividad = '{$idactividad}', nombre = '{$nombre}', descripcion = '{$descripcion}' WHERE idtarea = '{$idtarea}'";
            if ($this->db->query($sql))
                return true;
	 		else
                return false;

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    /*
    *   Función que dada una id de tarea que pertenece al usuario identificado, la borra de la BD
    */
    function borrarTareaUsuario($idtarea)
    {
        // Limpiamos los parametros de entrada
        $idtarea = $this->db->real_escape_string($idtarea);

        try
        {
            $sql = "DELETE FROM tareas WHERE idtarea = '{$idtarea}'";
            
            if ($this->db->query($sql))
                return true;
            else
                return false;

        } catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }
}