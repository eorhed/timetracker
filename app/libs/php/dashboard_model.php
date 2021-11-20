<?php
require_once "model.php";

class Dashboard_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
	}

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