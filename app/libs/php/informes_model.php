<?php
require_once "model.php";

/*
*   Clase Informes_Model que se encarga de todas las operaciones en la BD relacionadas con la pantalla de Informes de la app
*/
class Informes_Model extends Model{
    
    private $db;
    function __construct($typeUser)
	{
		$this->db = parent::__construct($typeUser);
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

    /*
    *   Función que dado un id de usuario devuelve actividades que tengas registros de seguimiento asociados
    */
    function getActividadesUsuarioConRegistro($idusuario){
        try
        {
            $sql = "SELECT A.idactividad, A.actividad, SUM(R.duracion) AS duracion_total FROM actividades AS A INNER JOIN tareas AS T INNER JOIN registros AS R ON A.idactividad = T.idactividad AND T.idtarea = R.idtarea WHERE A.idusuario = '{$idusuario}' GROUP BY A.idactividad ORDER BY A.idactividad ASC;";
            $query = $this->db->query($sql);
        
            $actividades = array();
                while ($actividad = $query->fetch_object())
                {
                    $actividad->duracion_total = self::getHorasMinutosSegundos($actividad->duracion_total);
                    $actividades[] = $actividad;
                }
                    
        
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
    
    /*
    *   Función que dada una duracion en segundos devuelve la duracion en formato horas, minutos y segundos
    */
    static function getHorasMinutosSegundos($duracion_segs)
    {
        $horas = floor($duracion_segs / 3600);
        $minutos = floor(($duracion_segs - ($horas * 3600)) / 60);
        $segundos = $duracion_segs - ($horas * 3600) - ($minutos * 60);
    
        
        return $horas . "h " . $minutos . "m " . $segundos . "s";
    }

    /*
    *   Función que dado un id de usuario y un id de actividad devuelve las tareas relacionadas con esa actividad
    */
    function getTareasActividadUsuario($idusuario, $idactividad)
    {
        try
        {
            $sql = "SELECT A.actividad, T.nombre, SUM(R.duracion) AS duracion_total, SUM(R.duracion) / 3600 * A.precio_x_hora AS precio_total FROM actividades AS A LEFT JOIN tareas AS T ON A.idactividad = T.idactividad INNER JOIN registros AS R ON T.idtarea = R.idtarea WHERE A.idactividad = '{$idactividad}' AND A.idusuario = '$idusuario' GROUP BY T.idtarea ORDER BY T.idtarea ASC;";
            $query = $this->db->query($sql);
            
            $tareas = array();
            while ($tarea = $query->fetch_object())
            {
                $tarea->duracion = $tarea->duracion_total; // Para el gráfico
                $tarea->duracion_total = self::getHorasMinutosSegundos($tarea->duracion_total);
                $tareas[] = $tarea;
            }
                    
        
            if (isset($tareas) && count($tareas) > 0)
                return $tareas;
            else
                return 0;

        }catch(Exception $e)
        {
            echo "<div class='info-warning'>ERROR:".$e->getMessage()."</div>";
            die();
        }
    }
}
?>