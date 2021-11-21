<?php
require_once "model.php";

/*
*   Clase encargada de realizar operaciones en la BD relacionadas con las pantallas de Actividades
*   (actividades.php, crear-actividad.php, editar-actividad.php)
*/
class Actividades_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    /*
    *   Función que dadas unas condiciones pasadas en el array $condiciones devuelve una actividad de la tabla actividades
    */
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

    /*
    *   Función que devuelve todas las actividades de la tabla actividades de la BD
    */
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

    /*
    *   Función que dado un array con los datos de la actividad, la inserta en la tabla actividades de la BD
    */
    function insertarActividadBD($actividad){
        
        try
        {
            $sql = "INSERT INTO actividades (idusuario,actividad,comentarios,fecha_inicio,fecha_fin,precio_x_hora) VALUES ('{$actividad["idusuario"]}', '{$actividad["actividad"]}', '{$actividad["comentarios"]}', '{$actividad["fechaInicio"]}', '{$actividad["fechaFin"]}', '{$actividad["precio_x_hora"]}')";
            $result =  $this->db->query($sql);
            $id = $this->db->insert_id;
            if (isset($id) && is_integer($id) && $id >0)
                return $id;
            else
                return NULL;
        }
        catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();

        }
    }

    /*
    *   Función que actualiza la actividad con los datos de la actividad (en forma de array asociativo) pasados como parametro en la tabla actividades de la BD
    */
    function editarActividad($actividad)
    {
        try
        {
            $sql = "UPDATE actividades SET actividad = '{$actividad["actividad"]}', comentarios = '{$actividad["comentarios"]}', precio_x_hora = '{$actividad["precio_x_hora"]}', fecha_inicio = '{$actividad["fecha_inicio"]}', fecha_fin = '{$actividad["fecha_fin"]}' WHERE idactividad = '{$actividad["idactividad"]}' AND idusuario = '{$actividad["idusuario"]}'";
            
            if ($this->db->query($sql))
                return true;
            else
                return false;
        }
        catch(Exception $e){
            echo 'ERROR:'.$e->getMessage()."<br>";
            die();
        }
    }

    /*
    *   Función que dado un id de usuario devuelve sus actividades
    */
    function getActividadesUsuario($idusuario){
        try{
            $sql = "SELECT * FROM actividades WHERE idusuario = '{$idusuario}' ORDER BY idactividad ASC;";
            $query = $this->db->query($sql);
        
            $actividades = array();
                while ($actividad = $query->fetch_object())
                    $actividades[] = $actividad;
        
            if (isset($actividades) && count($actividades)>0)
                return $actividades;
            else
                return 0;
        }catch(Exception $e)
        {
            echo "<div class='info-warning'>ERROR:".$e->getMessage()."</div>";
            die();
        }
	    
    }

    /*
    *   Función que dado un array con los datos de la actividad obtenidos del formulario, los valida
    */
    function validarActividad($actividad)
    {
        $actividad["actividad"] = isset($actividad['actividad']) ? mysqli::real_escape_string($actividad["actividad"]) : false;
        $actividad["cliente"] = isset($actividad['cliente']) ? mysqli::real_escape_string($actividad["cliente"]) : false;
        $actividad["comentarios"] = isset($actividad['comentarios']) ? mysqli::real_escape_string($actividad["comentarios"]) : false;
        $actividad["precio_x_hora"] = isset($actividad['precio_x_hora']) ? mysqli::real_escape_string($actividad["precio_x_hora"]) : false;

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

            if(count($respuesta['errores']) == 0){
        
                return $respuesta['success'] = true;
        
            }else{
                return $respuesta['errores'];
            }
    }

    /*
    *   Función que dado un id de usuario devuelve la primera actividad que tenga tareas asociadas a este del usuario
    *   Para la página de Trackear.php para que solo se carguen en el desplegable actividades con tareas y pueda ser "trackeable"
    */
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

    /*
    *   Función que dado un id de actividad y un id de usuario devuelve esa actividad de ese usuario
    */
    function getActividadUsuario($idactividad, $idusuario)
    {
        // Limpiamos las variables para su uso en la consulta SQL
        $idactividad = $this->db->real_escape_string($idactividad);
        $idusuario = $this->db->real_escape_string($idusuario);

        try
        {
            $sql = "SELECT * FROM actividades WHERE idactividad = '$idactividad' AND idusuario = '$idusuario'";
            $query =  $this->db->query($sql);
            
            if ($query->num_rows == 1)
                $resultado = $query->fetch_object();
                
            $query->close();
                
            if (isset($resultado))
                return $resultado;
            else
                return NULL;
        }
        catch(Exception $e)
        {
            echo "<div class='info-error'>Error:".$e->getMessage()."</div>";
        }
    }


    /*
     *   Dado un id de actividad y un idusuario borrará la actividad de dicho usuario
     *   de la tabla actividades de la BD, al borrar la actividad borrará en cascada
     *   las filas con referencias a esta actividad que tenga en las otras tablas (tareas y registros)
     */
    function borrarActividadUsuario($idactividad, $idusuario)
    {
        // Limpiamos los parametros de entrada
        $idactividad = $this->db->real_escape_string($idactividad);
        $idusuario = $this->db->real_escape_string($idusuario);

        try
        {
            $sql = "DELETE FROM actividades WHERE idactividad = '{$idactividad}' AND idusuario = '{$idusuario}'";
            
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