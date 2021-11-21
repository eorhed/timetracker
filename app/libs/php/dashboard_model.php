<?php
require_once "model.php";

class Dashboard_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

    /*
    *   Función que dado un id de usuario devuelve el numero total de actividades del usuario
    */
    function getNumTotalActividadesUsuario($idusuario)
    {
        
        $sql = "SELECT count(idactividad) as num_actividades FROM actividades WHERE idUsuario = '{$idusuario}'";

        $query =  $this->db->query($sql);
            $results = array();
            while ($row = $query->fetch_object()){
                $results[] = $row;
            }
            
            $query->close();
			
			if ($results) {
				return $results[0]->num_actividades;
			} else {
				$response['error'] = "No hay actividades";
				return $response;
			}

    }

    /*
    *   Función que dado un id de usuario devuelve el numero total de tareas de ese usuario
    */
    function getNumTotalTareasUsuario($idusuario)
    {

        $sql = "SELECT COUNT(*) as num_tareas FROM usuarios u inner join actividades a inner join tareas t on "
        ."u.idusuario=a.idusuario and a.idactividad = t.idactividad WHERE u.idusuario = '{$idusuario}'";

        $query =  $this->db->query($sql);
            $results = array();
            while ($row = $query->fetch_object()){
                $results[] = $row;
            }
            
            $query->close();
			
			if ($results) {                
				return $results[0]->num_tareas;
			} else {
				$response['error'] = "No hay tareas";
				return $response;
			}

    }

    /*
    *   Función que dado un id de usuario devuelve el numero de horas acumuladas de los registros de seguimiento de ese usuario
    */
    function getNumTotalHorasRegistradasUsuario($idusuario){
        

        try
        {
            $sql = "SELECT SUM(TIMESTAMPDIFF(HOUR,r.fecha_inicio,r.fecha_fin)) as num_horas "
            ."FROM usuarios u inner join actividades a inner join tareas t inner join registros r on "
            ."u.idusuario=a.idusuario and a.idactividad = t.idactividad and t.idtarea = r.idtarea WHERE u.idusuario = '{$idusuario}'";

            $query =  $this->db->query($sql);
            $results = array();
            while ($row = $query->fetch_object()){
                $results[] = $row;
            }
            
            $query->close();
			
			if (!$results[0]->num_horas)
                return 0;
            else
				return $results[0]->num_horas;
        }
        catch(Exception $e)
        {
            echo "<div class='info-error'>Error:".$e->getMessage()."</div>";
        }

    }

    /*
    *   Función que dado un id de usuario devuelve el total de dinero generado en las actividades que tienen un precio_x_hora > 0.
    *   es decir actividades lucrativas
    */
    function getTotalDineroGenerado($idusuario){


        $sql = "SELECT SUM(TIMESTAMPDIFF(HOUR,r.fecha_inicio,r.fecha_fin)*a.precio_x_hora) as num_horas "
            ."FROM usuarios u inner join actividades a inner join tareas t inner join registros r on "
            ."u.idusuario=a.idusuario and a.idactividad = t.idactividad and t.idtarea = r.idtarea WHERE u.idusuario = '{$idusuario}' and (a.precio_x_hora)>0";


        $query =  $this->db->query($sql);
        $results = array();
        while ($row = $query->fetch_object()){
            $results[] = $row;
        }
            
        $query->close();
			
		if ($results) {                
			return round($results[0]->num_horas,2);
		} else {
			$response['error'] = "No hay tareas";
			return $response;
		}
    }
}