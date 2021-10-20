<?php
class Model{
    
    private $db;

    function __construct($typeUser="user")
	{
		$fich_config = $_SERVER["DOCUMENT_ROOT"]."/timetracker/app/config/database.ini.php";

        if (file_exists($fich_config))
		{
			$config = parse_ini_file($fich_config);

            // Contraseñas robustas para los distintos usuarios de la BD de este sistema
            if ($typeUser == "user"){
                $userBD = $config["db_user_user"];
                $passwordBD = $config["db_user_pwd"];
            }
            else if ($typeUser == "admin"){
                $userBD = $config["db_admin_user"];
                $passwordBD = $config["db_admin_pwd"];
            }
            else{
                $userBD = $config["db_anom_user"];
                $passwordBD = $config["db_anom_pwd"];
            }
                
            $this->db = new mysqli($config["db_host"], $userBD, $passwordBD, $config["db_name"]);
            if ($this->db->connect_error)
                die("Connection failed: " . $this->db->connect_error);

            $this->db->set_charset('utf8');
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);  //Manejo de excepciones para mysqli
            
            return $this->db;
		}
	}

			//mysqli_close($this->db);

    function close()
    {
        $this->db->close();
    }
}